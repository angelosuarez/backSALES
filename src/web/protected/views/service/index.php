<?php
/* @var $this ServiceController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Servicios',
);

$this->menu=array(
	array('label'=>'Crear Servicio', 'url'=>array('create')),
	array('label'=>'Admin Servicios', 'url'=>array('admin')),
);
?>

<h1>Servicios</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
