<?php

class PurchasinggroupController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';
protected $menuname = 'purchasinggroup';

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
	  parent::actionCreate();
	  $purchasingorg=new Purchasingorg('searchwstatus');
	  $purchasingorg->unsetAttributes();  // clear any default values
	  if(isset($_GET['Purchasingorg']))
		$purchasingorg->attributes=$_GET['Purchasingorg'];
		$model=new Purchasinggroup;

		if (Yii::app()->request->isAjaxRequest)
        {
            echo CJSON::encode(array(
                'status'=>'success',
				));
            Yii::app()->end();
        }
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
	  parent::actionUpdate();
	  $purchasingorg=new Purchasingorg('searchwstatus');
	  $purchasingorg->unsetAttributes();  // clear any default values
	  if(isset($_GET['Purchasingorg']))
		$purchasingorg->attributes=$_GET['Purchasingorg'];
	  $model=$this->loadModel($_POST['id']);
if ($model != null)
      {
        if ($this->CheckDataLock($this->menuname, $_POST['id']) == false)
        {
          $this->InsertLock($this->menuname, $_POST['id']);
            echo CJSON::encode(array(
                'status'=>'success',
				'purchasinggroupid'=>$model->purchasinggroupid,
				'purchasingorgid'=>$model->purchasingorgid,
				'purchasingorgcode'=>$model->purchasingorg->purchasingorgcode,
				'purchasinggroupcode'=>$model->purchasinggroupcode,
				'description'=>$model->description,
				'recordstatus'=>$model->recordstatus,
				));
            Yii::app()->end();
        }
        }
	}

    public function actionCancelWrite()
    {
      $this->DeleteLockCloseForm($this->menuname, $_POST['Purchasinggroup'], $_POST['Purchasinggroup']['purchasinggroupid']);
    }

	public function actionWrite()
	{
	  parent::actionWrite();
	  if(isset($_POST['Purchasinggroup']))
	  {
        $messages = $this->ValidateData(
                array(array($_POST['Purchasinggroup']['purchasingorgid'],'emptypurchasingorgid','emptystring'),
array($_POST['Purchasinggroup']['purchasinggroupcode'],'emptypurchasinggroupcode','emptystring'),
                array($_POST['Purchasinggroup']['description'],'emptydescription','emptystring'),
            )
        );
        if ($messages == '') {
		//$dataku->attributes=$_POST['Purchasinggroup'];
		if ((int)$_POST['Purchasinggroup']['purchasinggroupid'] > 0)
		{
		  $model=$this->loadModel($_POST['Purchasinggroup']['purchasinggroupid']);
		  $this->olddata = $model->attributes;
			$this->useraction='update';
		  $model->purchasinggroupcode = $_POST['Purchasinggroup']['purchasinggroupcode'];
		  $model->purchasingorgid = $_POST['Purchasinggroup']['purchasingorgid'];
		  $model->description = $_POST['Purchasinggroup']['description'];
		  $model->recordstatus = $_POST['Purchasinggroup']['recordstatus'];
		}
		else
		{
		  $model = new Purchasinggroup();
		  $model->attributes=$_POST['Purchasinggroup'];
		  $this->olddata = $model->attributes;
			$this->useraction='new';
		}
		$this->newdata = $model->attributes;
		try
          {
            if($model->save())
            {
			$this->InsertTranslog();
              $this->DeleteLock($this->menuname, $_POST['Purchasinggroup']['purchasinggroupid']);
              $this->GetSMessage('insertsuccess');
            }
            else
            {
              $this->GetMessage($model->getErrors());
            }
          }
          catch (Exception $e)
          {
            $this->GetMessage($e->getMessage());
          }
        }
	  }
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
	  parent::actionDelete();
		$model=$this->loadModel($_POST['id']);
		  $model->recordstatus=0;
		  $model->save();
		echo CJSON::encode(array(
                'status'=>'success',
                'div'=>'Data deleted'
				));
        Yii::app()->end();
	}
	
	protected function gridData($data,$row)
  {     
    $model = Purchasinggroup::model()->findByPk($data->purchasinggroupid); 
    return $this->renderPartial('_view',array('model'=>$model),true); 
  }

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
	  parent::actionIndex();
	  $purchasingorg=new Purchasingorg('searchwstatus');
	  $purchasingorg->unsetAttributes();  // clear any default values
	  if(isset($_GET['Purchasingorg']))
		$purchasingorg->attributes=$_GET['Purchasingorg'];
		$model=new Purchasinggroup('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Purchasinggroup']))
			$model->attributes=$_GET['Purchasinggroup'];
	  if (isset($_GET['pageSize']))
	  {
		Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
		unset($_GET['pageSize']);  // would interfere with pager and repetitive page size change
	  }
		$this->render('index',array(
			'model'=>$model,
			'purchasingorg'=>$purchasingorg
		));
	}
  
  public function actionDownload()
	{
		parent::actionDownload();
		$sql = "select purchasingorgcode,purchasinggroupcode,a.description
				from purchasinggroup a 
				left join purchasingorg b on b.purchasingorgid = a.purchasingorgid ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.purchasinggroupid = ".$_GET['id'];
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		$this->pdf->title='Purchasing Group List';
		$this->pdf->AddPage('P');
		// definisi font
		$this->pdf->setFont('Arial','B',8);

		$this->pdf->colalign = array('C','C','C');
		$this->pdf->setwidths(array(50,50,90));
		$this->pdf->colheader = array('Purchasing Organization','Purchasing Group','Description');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1)
		{
		  $this->pdf->row(array($row1['purchasingorgcode'],$row1['purchasinggroupcode'],$row1['description']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Purchasinggroup::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='purchasinggroup-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
