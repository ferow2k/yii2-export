<?php

namespace Da\export\options;

use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use Yii;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQueryInterface;
use yii\grid\DataColumn;

abstract class OptionAbstract extends BaseObject implements OptionInterface
{
    use GridViewTrait;

    /**
     * @var ActiveDataProvider dataProvider
     */
    public $dataProvider;

    /**
     * @var array of columns
     */
    public $columns;

    /**
     * @var bool whether to export footer or not
     */
    public $exportFooter = true;

    /**
     * @var int batch size to fetch the data provider
     */
    public $batchSize = 2000;

    /**
     * @var string filename without extension
     */
    public $filename;

    /**
     * @see ExportMenu target consts
     * @var string how the page will delivery the report
     */
    public $target;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->initColumns();

        if (empty($this->filename)) {
            $this->filename = 'report_' . time();
        }
    }

    /**
     * Generate the row array
     *
     * @return array|void
     */
    protected function generateHeader()
    {
        if (empty($this->columns)) {
            return;
        }

        $rowArray = [];
        foreach ($this->columns as $column) {
            /** @var Column $column */
            $head = ($column instanceof DataColumn) ? $this->getColumnHeader($column) : $column->header;
            $rowArray[] = WriterEntityFactory::createCell($head);
        }
        return $rowArray;
    }

    /**
     * Fetch data from the data provider and create the rows array
     *
     * @return array|void
     */
    protected function generateBody()
    {
        if (empty($this->columns)) {
            return;
        }

        $rows = [];
        if ($this->dataProvider instanceof ActiveQueryInterface) {
            $query = $this->dataProvider->query;
            foreach ($query->batch($this->batchSize) as $models) {
                /**
                 * @var int $index
                 * @var \yii\db\ActiveRecord $model
                 */
                foreach ($models as $index => $model) {
                    $key = $model->getPrimaryKey();
                    $rows[] = $this->generateRow($model, $key, $index);
                }
            }
        } else {
            $this->dataProvider->pagination->pageSize = $this->batchSize;
            $models = $this->dataProvider->getModels();

            while (count($models) > 0) {
                /**
                 * @var int $index
                 * @var \yii\db\ActiveRecord $model
                 */
                $keys = $this->dataProvider->getKeys();
                foreach ($models as $index => $model) {
                    $key = $keys[$index];
                    $rows[] = $this->generateRow($model, $key, $index);
                }

                if ($this->dataProvider->pagination) {
                    $this->dataProvider->pagination->page++;
                    $this->dataProvider->refresh();
                    $models = $this->dataProvider->getModels();
                } else {
                    $models = [];
                }
            }
        }

        return $rows;
    }

    /**
     * Generate the row array
     *
     * @param $model
     * @param $key
     * @param $index
     * @return array
     */
    protected function generateRow($model, $key, $index)
    {
        $row = [];
        foreach ($this->columns as $column) {
            $value = $this->getColumnValue($model, $key, $index, $column);
            $row[] = $value;
        }
        return $row;
    }

    /**
     * Get the column generated value from the column
     *
     * @param $model
     * @param $key
     * @param $index
     * @param $column
     * @return string
     */
    protected function getColumnValue($model, $key, $index, $column)
    {
        /** @var Column $column */
        if ($column instanceof ActionColumn || $column instanceof CheckboxColumn) {
            return '';
        } elseif ($column instanceof DataColumn) {
            $val = $column->getDataCellValue($model, $key, $index);
            return Yii::$app->formatter->format($val, $column->format);
        } elseif ($column instanceof Column) {
            return $column->renderDataCell($model, $key, $index);
        }

        return '';
    }

    /**
     * generate footer row array
     *
     * @return array|void
     */
    protected function generateFooter()
    {
        if (!$this->exportFooter) {
            return;
        }

        if (empty($this->columns)) {
            return;
        }

        $rowsArray = [];
        foreach ($this->columns as $n => $column) {
            /** @var Column $column */
            $rowsArray[] = trim($column->footer) !== '' ? $column->footer : '';
        }
        return $rowsArray;
    }
}
