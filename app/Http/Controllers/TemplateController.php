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
    /*
     * Страницы со списком Шаблонов
     * Ex: template/1
     *
     * */
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

        if (empty($template)) {
            return view('templates.no-result', ['templates' => $templates]);
        }

        return view('templates.index', ['templates' => $templates]);

    }

    /*
     * Страница создания шаблона (Создание парсинга)
     * Ex: templates/create
     *
     * */
    public function create()
    {
        return view('templates.create');
    }


    /*
     * Контроллер сохранения шаблона в базу
     *
     * */
    public function store(Request $request, Template $template, Field $field)
    {

        $inputs = $request->input();

        /* Удаляем лишний запрос */
        unset($inputs['_token']);

        if (count($inputs) > 20) {
            return redirect()->back()->with('error', 'Слишком много полей');
        }


        /* Валидация запросов */

        $validateRule = []; // Массив для правил валидации

        foreach ($inputs as $key => $value) {
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

        /* Создание новой записи шаблона в БД и возвращение ID этой записи */
        $id = $template->createNewTemplate($request->input('queryName'));

        /* Create new Fields entry with reference to Template on DB */
        unset($inputs['queryName']);
        $field->createNewField($inputs, $id);

        /* Если успешно возвращаемся на страницу своих шаблонов */
        return redirect('/templates');
    }

    /*
     * Удаление записи шаблона и всего его содержимого из БД
     * !!!(в том числе и обьявления будут удалены по Каскаду)
     *
     * */
    public function destroy($id)
    {
        $template = Template::find($id);
        $template->delete();

        return redirect()->back()->with('succes', 'Запрос успешно удален');

    }
}
