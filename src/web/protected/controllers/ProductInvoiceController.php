<?php

class ProductInvoiceController extends Controller
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
                        array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','admin','create'),
				'users'=>array('*'),
			),
                        array('allow', // allow admin user to perform 'update' and 'delete' actions
				'actions'=>array('delete','update'),
				'users'=>Users::usersByType(1),
                                
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('delete','update'),
				'users'=>Users::usersByType(2),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('delete','update'),
				'users'=>Users::usersByType(3),
			),
			
			array('deny',  // deny all users
                                'actions'=>array('delete','update'),
				'users'=>Users::usersByType(4)
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
		$model=new ProductInvoice;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ProductInvoice']))
		{
			$model->attributes=$_POST['ProductInvoice'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
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

		if(isset($_POST['ProductInvoice']))
		{
                        $beforeQuantity=$model->quantity;
                        $afterQuantity=$_POST['ProductInvoice']['quantity'];
			$model->quantity=$afterQuantity;
			if($model->save()){
                            $products=Product::getData($model->id_product);
                            $products->quantity=abs(($products->quantity + $beforeQuantity) - $afterQuantity);
                            $products->save();
                            $this->redirect(array('/BikeCustomer/view','id'=>$model->id_bike_customer));
                        }
				
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
                $model=$this->loadModel($id);
                $bikeCustomerId=$model->id_bike_customer;
                $products=Product::getData($model->id_product);
                $products->quantity=abs($products->quantity + $model->quantity);
                if($products->save())
                {
                    $this->loadModel($id)->delete();
			$this->redirect(array('/BikeCustomer/view','id'=>$bikeCustomerId));
                
                }
		
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('ProductInvoice');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ProductInvoice('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ProductInvoice']))
			$model->attributes=$_GET['ProductInvoice'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ProductInvoice the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ProductInvoice::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ProductInvoice $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-invoice-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
