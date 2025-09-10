<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Question;
use App\Models\OrderRate;
use App\Models\Answer; 
use Illuminate\Http\Request;

class VisitorsController extends Controller
{
    public function rate($id)
    {
        $order = Order::findOrFail($id);
        $questions = Question::all();

        return view('rate', [
            'order' => $order,
            'questions' => $questions
        ]);
    }

    public function rateStore(Request $request)
    {
        // Custom validation messages
        $messages = [
            'order_id.required' => 'The order ID is required.',
            'order_id.exists' => 'The specified order does not exist.',
            'rating.required' => 'The rating is required.',
            'rating.integer' => 'The rating must be an integer.',
            'rating.min' => 'The rating must be at least 1.',
            'rating.max' => 'The rating may not be greater than 5.',
            'questions.required' => 'You must answer all questions.',
            'questions.array' => 'Questions must be an array.',
            'questions.*.boolean' => 'Each answer must be true or false.',
        ];

        // Custom attribute names
        $attributes = [
            'order_id' => 'order ID',
            'rating' => 'rating',
            'review' => 'review',
            'questions' => 'questions'
        ];

        // Validate the incoming request data
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'questions' => 'required|array',
            'questions.*' => 'boolean', // التأكد من أن كل إجابة هي Boolean
        ], $messages, $attributes);

        // Find existing rating for the order or create a new one
        $orderRating = OrderRate::updateOrCreate(
            ['order_id' => $validatedData['order_id']],
            [
                'rating' => $validatedData['rating'],
                'review' => $validatedData['review']
            ]
        );

        // Save answers to the questions
        foreach ($request->questions as $questionId => $answer) {
            Answer::create([
                'user_id' => auth()->id(), // استخدم ID المستخدم الحالي
                'question_id' => $questionId,
                'response' => $answer,
            ]);
        }

        // Return a response
        return redirect()->back()->with('success', 'Rating submitted successfully. Thanks!');
    }
}
