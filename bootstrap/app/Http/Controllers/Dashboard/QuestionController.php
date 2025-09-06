<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::withCount('answers')->get();
        return view('dashboard.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('dashboard.questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
        ]);

        Question::create([
            'question' => $request->question,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('questions.index')->with('success', 'تم حفظ السؤال بنجاح.');
    }

    public function edit($id)
    {
        $question = Question::findOrFail($id);
        return view('dashboard.questions.edit', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
        ]);

        $question = Question::findOrFail($id);
        $question->update(['question' => $request->question]);

        return redirect()->route('questions.index')->with('success', 'تم تحديث السؤال بنجاح.');
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('questions.index')->with('success', 'تم حذف السؤال بنجاح.');
    }

    public function storeAnswer(Request $request, $questionId)
    {
        $request->validate([
            'answer' => 'required|boolean',
        ]);

        Answer::create([
            'user_id' => auth()->id(),
            'question_id' => $questionId,
            'response' => $request->answer,
        ]);

        return redirect()->route('questions.index')->with('success', 'تم تسجيل إجابتك بنجاح!');
    }
    public function showAnswers($id)
    {
        $question = Question::with('answers.user')->findOrFail($id); // التأكد من جلب الإجابات مع أسماء العملاء
        return view('dashboard.questions.answers', compact('question'));
    }
    public function showUserAnswers($userId)
    {
        $userAnswers = Answer::with('question')->where('user_id', $userId)->get();

        return view('dashboard.questions.user', compact('userAnswers'));
    }
}

