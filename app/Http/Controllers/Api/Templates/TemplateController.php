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
        // $template = collect($this->mailer->getTemplate($alias));

        $template = $this->mailer->getTemplate($alias);

        $template = [
            'id' => $template['templateid'],
            "alias" => $template['alias'],
            'name' => $template['name'],
            'subject' => $template['subject'],
            "active" => $template['active'],
            "templateType" => $template['templatetype'],
            'htmlBody' => $template['htmlbody'],
            'textBody' => $template['textbody']
        ];


        return response()->json($template);
    }

    public function create(Request $request)
    {
        $response = $this->mailer->createTemplate($request->name, $request->subject, $request->htmlBody, $request->textBody);

        Template::create([
            'subject' => $request->subject,
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
        $template->name = $request->name;
        $template->subject = $request->subject;
        $response = $this->mailer->editTemplate($template->id, $template->name, $request->subject, $request->htmlBody, $request->textBody, $alias);
    }

    public function delete(Request $request, $alias)
    {
        $template = Template::where('alias', $alias)->first();
        $response = $this->mailer->deleteTemplate($template->id);
        if ($response['ErrorCode'] == 0) {
            $template->delete();
            return response()->json('Deleted', 200);
        }

        return response()->json('Not Deleted', 400);
    }
}
