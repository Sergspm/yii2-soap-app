<?php declare(strict_types=1);

namespace frontend\controllers;

use Codeception\Module\SOAP;
use common\models\LoginForm;
use Exception;
use frontend\models\CalculatorForm;
use SoapClient;
use SoapFault;
use SoapHeader;
use SoapServer;
use SoapVar;
use Yii;
use yii\base\InlineAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'login', 'index', 'calculate'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'logout', 'calculate'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function(){
                    $this->redirect('/');
                }
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @param InlineAction $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($action->id === 'calculate-soap') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * @return string|Response
     */
    public function actionIndex()
    {
        $model = new CalculatorForm();

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionWsdl()
    {
        return $this->renderPartial('wsdl', [
            'host' => $_SERVER['HTTP_HOST'],
        ]);
    }

    /**
     * @return array
     * @throws SoapFault
     */
    public function actionCalculate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $form = new CalculatorForm();

        $form->load(Yii::$app->request->post());

        $args = [];

        $args[] = $form->city;
        $args[] = $form->name;
        $args[] = $form->date;
        $args[] = $form->persons;
        $args[] = $form->bed_count;
        $args[] = $form->has_child;

        $client = new SoapClient("http://{$_SERVER['HTTP_HOST']}/wsdl");

        $vars = new SoapVar((object)['username' => 'root', 'password' => 'qSDeGverFDVw4avqWAE'], SOAP_ENC_OBJECT);
        $header = new SoapHeader('Auth', 'authenticate', $vars);

        $client->__setSoapHeaders([$header]);

        try {
            $result = json_decode($client->__soapCall('calculate', $args), true);
        } catch (SoapFault $e) {
            $result = ['error' => $e->faultstring];
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage()];
        }

        return $result;
    }

    /**
     *
     */
    public function actionCalculateSoap()
    {
        $server = new SoapServer("http://{$_SERVER['HTTP_HOST']}/wsdl");
        $server->setClass(CalculatorForm::class);
        $server->handle();
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
