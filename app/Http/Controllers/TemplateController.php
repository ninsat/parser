<?php

namespace App\Http\Controllers;

use App\Classes\Helper;
use App\Models\Ad;
use App\Models\Field;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exception;


class TemplateController extends Controller
{
    /*
     * Страница со списком всех шаблонов пользователя
     * Ex: template/1
     *
     * */
    public function all()
    {
        $templates = DB::table('templates')
            ->select('id', 'name', 'work')
            ->where('user_id', '=', Auth::user()->id)
            ->get();

        if (count($templates) < 1) {
            return view('templates.no-result', ['templates' => $templates]);
        }

        /* Статистика запросов */
        foreach ($templates as $template) {
            $template->countAdsParsed = Ad::countAdsParsed($template->id);
            $template->countAdsFetched = Ad::countAdsFetched($template->id);
            $template->countAdsIgnored = Ad::countAdsIgnored($template->id);
            $template->lastUpdateDateFetched = Ad::lastUpdateFetched($template->id);
            $template->lastUpdateDateParsed = Ad::lastUpdateParsed($template->id);
        }

        return view('templates.all', ['templates' => $templates]);
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

        if (!Helper::urlValidate($request->input('mainUrl'))) {
            return redirect()->back()->withErrors('Укажите правильный url');
        }

        /* Создание новой записи шаблона в БД и возвращение ID этой записи */
        $id = $template->createNewTemplate($request->input('queryName'));

        /* Create new Fields entry with reference to Template on DB */
        unset($inputs['queryName']);
        $field->createNewField($inputs, $id);

        /* Если успешно возвращаемся на страницу своих шаблонов */
        return redirect('/templates/all')->with('succes', 'Запрос успешно создан, не забудьте запустить его в работу');
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

    /* Включение пользовательского запроса на добавление новых объявлений */
    public function control(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'templateStart' => 'alpha_dash',
            'templateStop' => 'alpha_dash',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $result = false;

        if (isset($request->templateStart)) {
            $result = Template::startTemplateToWork($request->templateStart);

            if (!empty($result)) {
                return redirect()->back()->with('succes', 'Запрос успешно поставлен в работу, обновления появляются раз в пять минут');
            }
        }

        if (isset($request->templateStop)) {
            $result = Template::stopTemplateFromWork($request->templateStop);

            if (!empty($result)) {
                return redirect()->back()->with('succes', 'Запрос успешно остановлен');
            }
        }

        return $result;
    }

}