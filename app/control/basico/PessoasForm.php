<?php

//<fileHeader>

//</fileHeader>

use Adianti\Control\TAction;
use Adianti\Control\TWindow;
use Adianti\Database\TTransaction;
use Adianti\Validator\TRequiredValidator;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TToast;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TDate;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Form\TRadioGroup;
use Adianti\Wrapper\BootstrapFormBuilder;

class PessoasForm extends TWindow
{
    protected $form;
    private $formFields = [];
    private static $database = 'pessoas';
    private static $activeRecord = 'Pessoas';
    private static $primaryKey = 'id';
    private static $formName = 'form_PessoasForm';

    //<classProperties>

    //</classProperties>

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setSize(0.8, null);
        parent::setTitle("Cadastro de pessoas");
        parent::setProperty('class', 'window_modal');

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de pessoas");

        //<onBeginPageCreation>

        //</onBeginPageCreation>

        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $data_nascimento = new TDate('data_nascimento');
        $cpf = new TEntry('cpf');
        $sexo = new TRadioGroup('sexo');
        $fone = new TEntry('fone');
        $email = new TEntry('email');
        $rua = new TEntry('rua');
        $numero = new TEntry('numero');
        $complemento = new TEntry('complemento');
        $cidade = new TCombo('cidade');
        $uf = new TEntry('uf');

        $nome->addValidation("Nome", new TRequiredValidator()); 
        $data_nascimento->addValidation("Data nascimento", new TRequiredValidator()); 
        $cpf->addValidation("Cpf", new TRequiredValidator()); 
        $email->addValidation("Email", new TRequiredValidator()); 
        $numero->addValidation("Numero", new TRequiredValidator()); 
        $cidade->addValidation("Cidade", new TRequiredValidator()); 
        $uf->addValidation("Uf", new TRequiredValidator()); 

        $id->setEditable(false);
        $data_nascimento->setMask('dd/mm/yyyy');
        $data_nascimento->setDatabaseMask('yyyy-mm-dd');
        $sexo->setLayout('vertical');
        $cidade->enableSearch();
        $sexo->addItems(["1"=>" Masculino","2"=>" Feminino"]);
        $cidade->addItems(["1"=>" Lajeado","2"=>" Arroio do Meio","3"=>" Estrela","4"=>" Encantado","5"=>" Santa Cruz do Sul","6"=>" Nova Bréscia","7"=>" Porto Alegre","8"=>" Outra"]);

        $cpf->setMaxLength(11);
        $rua->setMaxLength(20);
        $fone->setMaxLength(10);
        $nome->setMaxLength(255);
        $email->setMaxLength(25);

        $id->setSize(100);
        $sexo->setSize(80);
        $uf->setSize('100%');
        $cpf->setSize('100%');
        $rua->setSize('100%');
        $nome->setSize('100%');
        $fone->setSize('100%');
        $email->setSize('100%');
        $numero->setSize('100%');
        $cidade->setSize('100%');
        $complemento->setSize('100%');
        $data_nascimento->setSize(110);



        //<onBeforeAddFieldsToForm>

        //</onBeforeAddFieldsToForm>
        $row1 = $this->form->addFields([new TLabel("Código:", null, '14px', null, '100%'),$id]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Nome:", '#ff0000', '14px', null, '100%'),$nome],[new TLabel("Data nascimento:", '#ff0000', '14px', null, '100%'),$data_nascimento]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("CPF:", '#ff0000', '14px', null, '100%'),$cpf],[new TLabel("Sexo:", '#ff0000', '14px', null, '100%'),$sexo]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        $row4 = $this->form->addFields([new TLabel("Telefone:", null, '14px', null, '100%'),$fone],[new TLabel("Email:", '#ff0000', '14px', null, '100%'),$email]);
        $row4->layout = ['col-sm-6','col-sm-6'];

        $row5 = $this->form->addFields([new TLabel("Rua:", null, '14px', null, '100%'),$rua],[new TLabel("Numero:", '#ff0000', '14px', null, '100%'),$numero]);
        $row5->layout = ['col-sm-6','col-sm-6'];

        $row6 = $this->form->addFields([new TLabel("Complemento:", null, '14px', null, '100%'),$complemento],[new TLabel("Cidade:", '#ff0000', '14px', null, '100%'),$cidade]);
        $row6->layout = ['col-sm-6','col-sm-6'];

        $row7 = $this->form->addFields([new TLabel("UF:", '#ff0000', '14px', null, '100%'),$uf],[]);
        $row7->layout = ['col-sm-6','col-sm-6'];

        //<onAfterFieldsCreation>

        //</onAfterFieldsCreation>

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['PessoasHeaderList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        //<onAfterPageCreation>

        //</onAfterPageCreation>

        parent::add($this->form);

    }

//<generated-FormAction-onSave>
    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Pessoas(); // create an empty object //</blockLine>

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            //</beforeStoreAutoCode> //</blockLine>

            $object->store(); // save the object //</blockLine>

            //</afterStoreAutoCode> //</blockLine>
 //<generatedAutoCode>

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

//</generatedAutoCode>

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; //</blockLine>

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            //</messageAutoCode> //</blockLine>
//<generatedAutoCode>
            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('PessoasHeaderList', 'onShow', $loadPageParam);
//</generatedAutoCode>

            //</endTryAutoCode> //</blockLine>
//<generatedAutoCode>
    TWindow::closeWindow(parent::getId());
//</generatedAutoCode>
        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> //</blockLine>

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
//</generated-FormAction-onSave>

//<generated-onEdit>
    public function onEdit( $param )//</ini>
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Pessoas($key); // instantiates the Active Record //</blockLine>
                //</beforeSetDataAutoCode> //</blockLine>

                 $this->form->setData($object); // fill the form //</blockLine>

                //</afterSetDataAutoCode> //</blockLine>
                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }//</end>
//</generated-onEdit>

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

        //<onFormClear>

        //</onFormClear>

    }

    public static function onShow($param = null)
    {

        //<onShow>

        //</onShow>
    } 

    //</hideLine> <addUserFunctionsCode/>

    //<userCustomFunctions>

    //</userCustomFunctions>

}