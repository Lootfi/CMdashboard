<?php

namespace App\Http\Controllers\Api\Templates;

use Postmark\PostmarkClient;
use App\Http\Controllers\Controller;
use App\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    protected $mailer;

    function __construct()
    {
        $this->mailer = new PostmarkClient(env('POSTMARK_SECRET'));
    }


    public function index()
    {
        $templates = Template::all();

        return response()->json($templates);
    }

    public function show(Request $request, $alias)
    {
        $response = $this->mailer->getTemplate('welcome');

        return response()->json($response);
    }

    public function create(Request $request)
    {
        $response = $this->mailer->createTemplate($request->name, $request->subject, $request->htmlBody, $request->textBody);

        Template::create([
            'id' => $response->TemplateId,
            "name" => $response->Name,
            "active" => $response->Active,
            "alias" => $response->Alias,
            "templateType" => $response->TemplateType
        ]);
    }

    public function edit(Request $request, $alias)
    {
        $template = Template::where('alias', $alias)->first();
        $response = $this->mailer->editTemplate($template->id, $template->name, $request->subject, $request->htmlBody, $request->textBody, $alias);
    }
}