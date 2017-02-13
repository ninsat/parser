<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TemplateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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

        /* Validation */
        $validateRule = [];

        foreach($inputs as $key => $value) {
            $validateRule[$key] = 'required';
        }

        $validator = Validator::make($request->input(), $validateRule);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
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
