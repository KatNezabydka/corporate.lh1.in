<?php
namespace App\Repositories;

use Config;

/**
 * Каждая сущность бд будет работать со своим репозиторием
 * */

abstract class Repository {

    protected $model = FALSE;

    /**
     * Возвращает коллеекцию выбранных моделей
     * @param string $select
     * @param bool $take
     * @return mixed
     */
    public function get($select = '*', $take = FALSE, $pagination = FALSE, $where = FALSE) {
        //select - указывает какие поля нужно выбрать их бд
        $builder = $this->model->select($select);

        if($take) {
            //take() - выдирает заданое количество записей из бд
            $builder->take($take);
        }
        // для выбора статей конкретной категории; $where[0] - имя, $where[1] - значение
        if($where) {
            $builder->where($where[0], $where[1]);
        }

        //добавили пагинацию
        if($pagination) {
            return $this->check($builder->paginate(Config::get('settings.paginate')));
        }

        return $this->check($builder->get());
    }

    /**
     * Отобращаем img в нормальном виде...не в формате json
     * Если изначально он не был в формате json - то не трогаем
     * @param $result
     * @return bool
     */
    protected function check($result) {

        if($result->isEmpty()) {
            return FALSE;
        }
        $result->transform(function($item,$key) {
            //если строка, если декодирование возвращает строку, и если декодирование не дало ошибок
            if(is_string($item->img) && is_object(json_decode($item->img)) && (json_last_error() == JSON_ERROR_NONE)) {

                //декодируем строку формата json в объект
                $item->img = json_decode($item->img);
            }


            return $item;
        });

        return $result;

    }

    public function one($alias, $attr = array()) {

       $result = $this->model->where('alias',$alias)->first();
       return $result;

    }

    /**
     * @param $string
     * transliterate - переводит в латинские буквы из строки
     */
    public function transliterate($string) {
        //перевели все в нижний регистр
        $str = mb_strtolower($string, 'UTF-8');

        //определяем сообтветствие кирилицы и латиницы
        $leter_array = array(
            'a' => 'а',
            'b' => 'б',
            'v' => 'в',
            'g' => 'г',
            'd' => 'д',
            'e' => 'е,э',
            'jo' => 'ё',
            'zh' => 'ж',
            'z' => 'з',
            'i' => 'и,і',
            'ji' => 'ї',
            'j' => 'й',
            'k' => 'к',
            'l' => 'л',
            'm' => 'м',
            'n' => 'н',
            'o' => 'о',
            'p' => 'п',
            'r' => 'р',
            's' => 'с',
            't' => 'т',
            'u' => 'у',
            'f' => 'ф',
            'kh' => 'х',
            'ts' => 'ц',
            'ch' => 'ч',
            'sh' => 'ш',
            'shch' => 'щ',
            '' => 'ь,ъ',
            'y' => 'ы',
            'yu' => 'ю',
            'ya' => 'я',
        );

        foreach ($leter_array as $letter => $kyr) {
            //убрали разделитель , в русском языке
            $kyr = explode(',',$kyr);
            //поиск подстроки в строке
            $str = str_replace($kyr,$letter,$str);
        }
        //выполняет поиск и замену по регулятному выражению
        //1) искомый шаблон
        //2) во 2м на что мы будем заменять все, что не подходит
        //2) строку/массив строк для замены, и все символы что нас не устраивают или не входят в диапазон (например пробел)
        //3) строка/массив, с которым мы работаем
        //\s - пробельный символ | - или, A-Za-z0-9\- - это слова, цифры и тире
        $str = preg_replace('/(\s|[^A-Za-z0-9\-])+/','-',$str);
        //удаляем тире из конца
        $str  = trim($str);

        return $str;

    }

}