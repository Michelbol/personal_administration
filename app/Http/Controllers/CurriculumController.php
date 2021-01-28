<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormCurriculumRequest;
use App\Mail\FormContactCurriculum;
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
        return view('curriculum');
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
