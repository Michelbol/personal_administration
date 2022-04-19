<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormCurriculumRequest;
use App\Mail\FormContactCurriculum;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Mail;

class CurriculumController extends Controller
{

    /**
     * @return Application|Factory|View
     */
    public function curriculum()
    {
        $now = Carbon::now();
        $birthDay = Carbon::create(1995, 10, 11);
        $age = $now->diffInYears($birthDay);
        return view('curriculum', compact('age'));
    }

    /**
     * @param ContactFormCurriculumRequest $request
     * @return RedirectResponse
     */
    public function contact(ContactFormCurriculumRequest $request)
    {
        $data = $request->validated();
        Mail::send(new FormContactCurriculum(
            $data['name'],
            $data['email'],
            $data['message'],
            $data['subject'] ?? null
        ));
        $this->successMessage("E-mail enviado com sucesso, obrigado pelo contato!");
        return redirect()->route('curriculum');
    }
}
