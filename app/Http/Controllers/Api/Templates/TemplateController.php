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
            'id' => $response->TemplateId,
            'subject' => $request->subject,
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

    public function getTemplate()
    {
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.postmarkapp.com/templates/validate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'X-Postmark-Server-Token: 14f709d4-e0aa-4fdf-9ace-4cfadcc5f0c0';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        echo $result;
    }

    public function refresh()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.postmarkapp.com/templates?count=100&offset=0&TemplateType=Standard');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'X-Postmark-Server-Token: 14f709d4-e0aa-4fdf-9ace-4cfadcc5f0c0';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        foreach (json_decode($result)->Templates as $key => $template) {
            if ($existingTemplate = Template::where('id', $template->TemplateId)->first()) {
                $existingTemplate->name = $template->Name;
                $existingTemplate->active = $template->Active;
                $existingTemplate->alias = $template->Alias;
                $existingTemplate->save();
            } else {
                Template::create([
                    'id' => $template->TemplateId,
                    "name" => $template->Name,
                    "active" => $template->Active,
                    "alias" => $template->Alias,
                    "templateType" => $template->TemplateType
                ]);
            }
        }

        $templates = Template::all();

        return response()->json($templates);
    }
}
