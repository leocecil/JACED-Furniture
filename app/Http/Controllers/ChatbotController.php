<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        try {
            $request->validate([
                'messages' => 'required|array',
                'budget'   => 'nullable|integer',
            ]);

            $query = Product::with(['category', 'mainImage'])
                ->select('id', 'name', 'description', 'length', 'width', 'height', 'unit', 'price', 'stock', 'category_id');

            if ($request->budget) {
                $query->where('price', '<=', $request->budget);
            }

            $products = $query->get()->map(fn($p) => [
                'id'         => $p->id,
                'slug'       => $p->slug,
                'name'       => $p->name,
                'category'   => $p->category->name ?? 'Uncategorized',
                'price'      => 'Rp ' . number_format($p->price, 0, ',', '.'),
                'priceNum'   => (int) $p->price,
                'dimensions' => $p->length . ' × ' . $p->width . ' × ' . $p->height . ' ' . $p->unit,
                'description' => $p->description,
                'image_url'  => $p->mainImage ? asset($p->mainImage->image_path) : null,
                'in_stock'   => $p->stock > 0,
            ]);

            $budgetContext = $request->budget
                ? "\n\nIMPORTANT: Customer's max budget is Rp " . number_format($request->budget, 0, ',', '.') . ". Only recommend products within this budget."
                : '';

            $systemPrompt = "You are a friendly furniture consultant for " . config('app.name') . ".

You have access to this product catalog:
" . $products->toJson(JSON_PRETTY_PRINT) . "

When a customer asks about furniture:
1. Understand their needs: room size, style, budget
2. Recommend 2-3 specific products from the catalog
3. Explain briefly WHY each fits their needs
4. Be warm, concise, and helpful

CRITICAL: Your ENTIRE response must be ONLY a single valid JSON object. No text before or after it. No explanation. No markdown. Just the raw JSON.

The JSON structure must be exactly:
{
  \"message\": \"Your friendly response (1-3 sentences)\",
  \"products\": [
    {
      \"id\": 1,
      \"name\": \"Product Name\",
      \"category\": \"Category Name\",
      \"price\": \"Rp 1.000.000\",
      \"priceNum\": 1000000,
      \"dimensions\": \"100 x 50 x 75 cm\",
      \"description\": \"Product description\",
      \"image_url\": null
    }
  ],
  \"quick_replies\": [\"Option 1\", \"Option 2\", \"Option 3\"]
}
  
Rules:
- products array must contain 2-3 FULL product objects copied from the catalog above (not just IDs)
- Use [] for products only when just chatting with no recommendation
- quick_replies must be short phrases written from the CUSTOMER's perspective, things THEY would say next. Good: 'My room is 4x5 meters', 'I prefer minimalist style'. Bad: 'What is your room size?'
- Respond in the same language the customer uses (Indonesian or English)
- NEVER add any text outside the JSON object" . $budgetContext;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.groq.key'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'      => 'llama-3.3-70b-versatile',
                'max_tokens' => 2000,
                'messages'   => array_merge(
                    [['role' => 'system', 'content' => $systemPrompt]],
                    $request->messages
                ),
            ]);

            Log::info('Groq status: ' . $response->status());
            Log::info('Groq response: ' . json_encode($response->json()));

            $rawText = $response->json()['choices'][0]['message']['content'] ?? 'Sorry, no response.';

            return response()->json([
                'content' => [['text' => $rawText]]
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());
            return response()->json([
                'content' => [['text' => 'Sorry, no response.']]
            ], 500);
        }
    }
}