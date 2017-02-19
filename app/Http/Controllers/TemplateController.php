<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Field;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TemplateController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        $templates = DB::table('templates')
            ->select('id', 'name')
            ->where('user_id', '=', Auth::user()->id)
            ->get();

        /* Статистика запроса */
        foreach ($templates as $template) {
            $template->adsProcessed = Ad::where('fetched', 1)->where('template_id', $template->id)->count();
            $template->adsNotProcessed = Ad::where('fetched', 0)->where('template_id', $template->id)->count();
        }

        return view('templates.index', ['templates' => $templates]);

    }

    public function create()
    {
        return view('templates.create');
    }

    public function store(Request $request, Template $template, Field $field) {

        $inputs = $request->input();

        /* Remove not for fields request*/
        unset($inputs['_token']);

        if (count($inputs) > 20) {
            return redirect()->back()->with('error', 'Слишком много полей');
        }


        /* Validate fields */

        $validateRule = []; // Create array for rules

        foreach($inputs as $key => $value) {
            if ($key === 'queryName') {
                $validateRule[$key] = 'required|min:5|max:255';
            } elseif ($key === 'mainUrl') {
                $validateRule[$key] = 'required|max:255';
            } else {
                $validateRule[$key] = 'required';
            }
        }

        $validator = Validator::make($request->input(), $validateRule);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $url = filter_var($request->input('mainUrl'), FILTER_VALIDATE_URL);
        if ($url === false) {
            return redirect()->back()->withErrors('Укажите правильный url');
        }

        /* Create new Template entry on DB and return own ID */
        $id = $template->createNewTemplate($request->input('queryName'));

        /* Create new Fields entry with reference to Template on DB */
        unset($inputs['queryName']);
        $field->createNewField($inputs, $id);

        return redirect('/templates');
    }

    public function destroy($id)
    {
        $template = Template::find($id);
        $template->delete();

        return redirect()->back()->with('succes', 'Запрос успешно удален');

    }
}
