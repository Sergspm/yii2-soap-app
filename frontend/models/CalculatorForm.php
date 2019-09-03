<?php declare(strict_types=1);

namespace frontend\models;

use DateTime;
use SoapFault;
use yii\base\Model;

/**
 * Class CalculatorForm
 * @package frontend\models
 */
class CalculatorForm extends Model
{
    public $city;
    public $name;
    public $date;
    public $persons;
    public $bed_count;
    public $has_child;

    protected $data = [];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['city', 'trim'],

            ['name', 'trim'],

            ['date', 'trim'],
            ['date', 'required'],
            ['date', 'match', 'pattern' => '/^\d{4}-\d\d-\d\d$/i'],
            ['date', function ($attr) {
                $diff = (new DateTime($this->{$attr}))->diff(new DateTime());
                if ($diff->days && !$diff->invert) {
                    $this->addError($attr, 'The date in past');
                }
            }],

            ['persons', 'trim'],
            ['persons', 'filter', 'filter' => 'intval'],

            ['bed_count', 'trim'],
            ['bed_count', 'filter', 'filter' => 'intval'],

            ['has_child', 'trim'],
            ['has_child', 'filter', 'filter' => 'boolval'],
        ];
    }

    /**
     * @param $params
     * @return bool
     * @throws SoapFault
     */
    public function authenticate($params)
    {
        if ($params->username === 'root' && $params->password === 'qSDeGverFDVw4avqWAE') {
            return true;
        }

        throw new SoapFault('auth', 'Wrong user or password');
    }

    /**
     * @param string $city
     * @param string $name
     * @param string $date
     * @param string $persons
     * @param string $bed_count
     * @param string $has_child
     * @return CalcSoapResponse
     */
    public function calculate($city, $name, $date, $persons, $bed_count, $has_child): CalcSoapResponse
    {
        $this->city = $city;
        $this->name = $name;
        $this->date = $date;
        $this->persons = $persons;
        $this->bed_count = $bed_count;
        $this->has_child = $has_child;

        $response = new CalcSoapResponse();

        if ($this->validate()) {
            $response->price = rand(1, 1000);
            $response->info = md5(time() . '' . rand());
        } else {
            $response->error = $this->getFirstError('date');
        }

        return $response;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
