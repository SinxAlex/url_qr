<?php

namespace app\controllers;

use app\models\UrlLogModel;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UrlModel;
use Da\QrCode\QrCode;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model=new UrlModel();

        return $this->render('index',['model'=>$model]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

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
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionGetQr()
    {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $out = ['success' => false, 'response_error' => '', 'data' => ''];

            $url_to=Yii::$app->request->post('UrlModel')['url_to'];
            if(!$model=UrlModel::findOne(['url_to'=>$url_to]))
            {
                  $model = new UrlModel();
                  $model->views=0;
             }
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->views=$model->views+1;
                $model->save();

                $qrCode = (new QrCode($model->url_to))
                    ->setSize(250)
                    ->setMargin(5)
                    ->setBackgroundColor(51, 153, 255);
                $out['success'] = true;
                $out['data'] = [
                    'url'=>$model->url_to,
                    'url-image'=>'data:image/png;base64,'.base64_encode($qrCode->writeString()),
                    'short_url'=>$model->url_short,
                    'views'=>$model->views,
                    ];
            } else {
                $out['error'] = json_encode($model->errors);
            }
            return $out;
        }

        public function actionRedirectQr($url)
        {
            $model=UrlModel::findOne(['url_to'=>$url]);
            if($model){
                $model_log=new UrlLogModel();
                $model_log->id_url=$model->id;
                $model_log->save();
            }
            return $this->render('redirect',['url'=>\yii\helpers\Url::to($url)]);

        }

        public function actionJournal()
        {

            $provider = new ActiveDataProvider([
                'query' => UrlLogModel::find(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);

            return $this->render('journal',['provider'=>$provider]);

        }
}
