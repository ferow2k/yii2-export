<?php

namespace Da\export;

class GridView extends \kartik\grid\GridView
{
    public $layout = "{export}\n{summary}\n{items}\n{pager}";

    /**
     * @var array export config
     */
    public $exportConfig;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $exportMenu = $this->renderExportMenu();
        $this->layout = strtr(
            $this->layout,
            [
                '{export}' => $exportMenu,
            ]
        );

        parent::run();
    }

    /**
     * Method that calls and return ExportMenu html
     *
     * @return string
     * @throws \Exception
     */
    public function renderExportMenu()
    {
        $exportConfig = $this->exportConfig();

        return ExportMenu::widget($exportConfig);
    }

    /**
     * Method that include standard configurations to export config array
     *
     * @return array
     */
    private function exportConfig()
    {
        $exportConfig = $this->exportConfig;
        if (empty($exportConfig)) {
            $exportConfig = [];
        }

        return array_merge(
            $exportConfig,
            [
                'dataProvider' => $this->dataProvider,
                'columns' => $this->columns,
            ]
        );
    }
}
