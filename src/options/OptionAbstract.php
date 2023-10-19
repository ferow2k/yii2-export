<?php

namespace Da\export\options;

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
    public $batchSize = 500;

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
        $this->initColumns();

        if (empty($this->filename)) {
            $this->filename = 'report_' . time();
        }
    }

    /**
     * write the row array
     *
     * @return array|void
     */
    protected function writeHeader()
    {
        if (empty($this->columns)) {
            return;
        }

        $rowArray = [];
        foreach ($this->columns as $column) {
            /** @var Column $column */
            $head = ($column instanceof DataColumn) ? $this->getColumnHeader($column) : $column->header;
            $rowArray[] = $head;
        }
        $this->addRow($rowArray);
    }

    /**
     * Fetch data from the data provider and create the rows array
     *
     * @return array|void
     */
    protected function writeBody()
    {
        if (empty($this->columns)) {
            return;
        }

        if ($this->dataProvider instanceof ActiveQueryInterface) {
            $query = $this->dataProvider->query;
            foreach ($query->batch($this->batchSize) as $models) {
                /**
                 * @var int $index
                 * @var \yii\db\ActiveRecord $model
                 */
                foreach ($models as $index => $model) {
                    $key = $model->getPrimaryKey();
                    $this->writeRow($model, $key, $index);
                }
            }
        } else {
            $this->dataProvider->pagination->page = 0;
            $this->dataProvider->pagination->pageSize = $this->batchSize;
            $this->dataProvider->refresh();
            $models = $this->dataProvider->getModels();

            while (count($models) > 0) {
                /**
                 * @var int $index
                 * @var \yii\db\ActiveRecord $model
                 */
                $keys = $this->dataProvider->getKeys();
                foreach ($models as $index => $model) {
                    $this->writeRow($model, $keys[$index], $index);
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
    }

    /**
     * write the row array
     *
     * @param $model
     * @param $key
     * @param $index
     * @return array
     */
    protected function writeRow($model, $key, $index)
    {
        $row = [];
        foreach ($this->columns as $column) {
            $row[] = $this->getColumnValue($model, $key, $index, $column);
        }
        $this->addRow($row);
        unset($row);
    }

    abstract protected function addRow(array $row);

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
            if ($column->format == 'currency' || $column->format == 'decimal' || $column->format == 'percent') {
                return floatval($val);
            } elseif ($column->format == 'date' || $column->format == 'datetime') {
                return $val ? new \DateTime($val) : null;
            } else {
                return $val;
            }
            //return Yii::$app->formatter->format($val, $column->format);
        } elseif ($column instanceof Column) {
            return $column->renderDataCell($model, $key, $index);
        }

        return '';
    }

    /**
     * write footer row array
     *
     * @return array|void
     */
    protected function writeFooter()
    {
        if (!$this->exportFooter) {
            return;
        }

        if (empty($this->columns)) {
            return;
        }

        $row = [];
        foreach ($this->columns as $n => $column) {
            /** @var Column $column */
            $row[] = trim($column->footer) !== '' ? $column->footer : '';
        }
        $this->addRow($row);
    }

    protected function writeFile()
    {
        $this->writeHeader();
        $this->writeBody();
        $this->writeFooter();
    }
}
