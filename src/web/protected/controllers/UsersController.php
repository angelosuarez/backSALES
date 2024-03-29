<?php

class UsersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'update' and 'delete' actions
				'actions'=>array('error'),
				'users'=>array("*"),    
			),
			array('allow', // allow admin user to perform 'update' and 'delete' actions
				'actions'=>array('delete','update','index','view','admin', 'create'),
				'users'=>Users::usersByType(1),    
			),
			array('allow', // allow admin user to perform 'update' and 'delete' actions
				'actions'=>array('delete','update','index','view','admin', 'create'),
				'users'=>Users::usersByType(2),    
			),
			array('deny', // allow admin user to perform 'update' and 'delete' actions
				'actions'=>array('delete','update','index','view','admin', 'create'),
				'users'=>Users::usersByType(3),    
			),
			array('deny', // allow admin user to perform 'update' and 'delete' actions
				'actions'=>array('delete','update','index','view','admin', 'create'),
				'users'=>Users::usersByType(4),    
			),

		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Users;

		// Uncomment the following line if AJAX validation is needed
		 $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
                        $exist=Users::getUsernameExist($_POST['Users']['username']);
                        if($exist==NULL)
                        {
                            $model->name=strtoupper($_POST['Users']['name']);
                            $model->lastname=strtoupper($_POST['Users']['lastname']);
                            $model->phone=$_POST['Users']['phone'];
                            $model->username=strtoupper($_POST['Users']['username']);
                            $model->password=md5($_POST['Users']['password']);
                            $model->email=$_POST['Users']['email'];
                            $model->status=$_POST['Users']['status'];
                            $model->id_type_of_user=$_POST['Users']['id_type_of_user'];
                            $model->superuser=TRUE;
                            $model->create_at=date("Y-m-d");
                            $model->lastvisit_at=date("Y-m-d");
                            $model->activkey='1d6ccd492bc1a2da9fea83c081d06131';
                            if($model->save())
                                    $this->redirect(array('view','id'=>$model->id));
                        }else{
                            $this->redirect("error");
                        }
		}
		$this->render('create',array(
			'model'=>$model,
		));
	}
        
        public function actionError()
        {
            $this->render('error', array('link'=>'Volver a Crear Usuario','action'=>'create', 'message'=>"El usuario que intenta crear ya se encuentra registrado..."));
        }
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
                        $model->name=strtoupper($_POST['Users']['name']);
                        $model->lastname=strtoupper($_POST['Users']['lastname']);
                        $model->username=strtoupper($_POST['Users']['username']);
                        if($_POST['Users']['new_password']!="")
                            $model->password=md5($_POST['Users']['new_password']);
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Users');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Users the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Users $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
