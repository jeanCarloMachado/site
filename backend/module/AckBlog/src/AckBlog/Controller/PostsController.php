<?php
namespace AckBlog\Controller;

use AckMvc\Controller\AbstractTableRowController as Controller;
use Zend\View\Model\ViewModel;

class PostsController extends Controller
{
    protected $models = array('default'=>'\AckBlog\Model\Posts');

    protected $config = array(
        'global' => array(
            'blacklist' => array(
                'id', 'ordem', 'status', 'visivel', 'tipo', 'conteudo'
            ),
            'plugins' => array(
                'metatags'
            ),

        ),
        'lista' => array(
            'whitelist' => array(
                'titulo', 'publicado', 'data'
            ),
        ),
    );

    protected function evtAfterGetScopedData()
    {
        if ($this->params("action") == "editar" || $this->params("action") == "incluir" ) {

            $config = $this->viewModel->config;
            $select = \AckCore\HtmlElements\Select::Factory(
                $this->viewModel->config["row"]->vars["tipo"]
            )->setPermission(2);
            $select->setOption(1,'HTML', ($config['row']->getTipo()->getBruteVal() == 1));
            $select->setOption(2,'Markdown', ($config['row']->getTipo()->getBruteVal() == 2));

            $config["toRenderCOL1"][1] = $select;

            $content = \AckCore\HtmlElements\TextEditor::Factory($this->viewModel->config["row"]->vars["conteudo"]);

            if ($config['row']->getTipo()->getBruteVal() == 2) {
                $content->setMarkdown(true);
            }

            $content->setPermission(2);

            $config["toRenderCOL1"][2] = $content;

            $this->viewModel->config = $config;
        }
    }

    public function visualizarAction()
    {
        $id = $this->params('id');
        $data = array();

        $entity = $this->getModelInstance()
            ->toObject()
            ->onlyAvailable()
            ->getOne(array('id'=>$id));

        if (!$entity->getPublicado()->getBruteVal()) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $data['row'] = $entity;

        if ($entity->getTipo()->getBruteVal() == \AckBlog\Model\Posts::TYPE_MARKDOWN) {
            $data['isMarkdown'] = true;
        } else {
            $data['isMarkdown'] = false;
        }


        $this->viewModel->setVariables($data);

        return $this->viewModel;
   }

    public function jsonAction()
    {
        $id = $this->params('id');
        $data = array();

        $entity = $this->getModelInstance()
            ->toObject()
            ->onlyAvailable()
            ->getOne(array('id'=>(int) $id));

        $data['row'] = $entity;

        if ($entity->getTipo()->getBruteVal() == \AckBlog\Model\Posts::TYPE_MARKDOWN) {
            $data['isMarkdown'] = true;
        } else {
            $data['isMarkdown'] = false;
        }

        $data['metatag'] = $this->getMetatagForEntity($entity);

        $json = json_encode($data, true);
        echo $json;

        return $this->getResponse();
    }

    private function getMetatagForEntity($entity)
    {
        $metatagsModel = $this->getServiceLocator()->get('Metatags');

        $where = array('class_name' => $entity->getTableModelName(),'related_id' => $entity->getId()->getBruteVal());
        return $metatagsModel->toObject()->getOne($where);
    }
}