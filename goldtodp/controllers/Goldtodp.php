<?php class goldtodp extends MX_Controller
{

    private $PerPage = 25;
    const MODULE_NAME = 'goldtodp';
    const MODULE_PATH = 'modules/goldtodp';

    private $realm;
    private $guid;


    public function __construct()
    {
        parent::__construct();

        $this->user->userArea();

        $this->load->config('config');
        $this->load->model('data_model');
    }


    public function index()
    {
        requirePermission("view");

        $this->template->setTitle($this->lang('TITLE', 'goldtodp'));

        // Prepare data
        $data = array(
            "module" => "goldtodp",
            "image_path" => $this->template->image_path
        );

        // Load the template file and format
        $ajax = $this->template->loadPage("ajax.tpl", $data);

        // Load the topsite page and format the page contents
        $data2 = array(
            "module" => "default",
            "headline" => $this->lang('TITLE', 'goldtodp'),
            "content" => $ajax
        );

        $page = $this->template->loadPage("page.tpl", $data2);

        $this->template->view($page, "modules/goldtodp/css/characters.css", "modules/goldtodp/js/characters.js");
    }

    public function Load()
    {
        $content_data = array(

            "url" => $this->template->page_url,
            "dp" => $this->user->getDp(),
            "config" => $this->config,
            "this" => $this,
            "realms" => $this->realms->getRealms(),
            "Server_fee" => $this->config->item('Server_fee')

        );

        $page_content = $this->template->loadPage("char_item.tpl", $content_data);

        //Load the page
        $page_data = array(
            "module" => "default",
            "headline" => $this->lang('TITLE', 'goldtodp'),
            "content" => $page_content
        );

        die($page_content);
    }


    public function process()
    {
        $Price = (int)$this->config->item('Server_fee');
        $PriceCurrency = $this->config->item('price_currency');
        $characterGuid = $this->input->post('guid');
        $realmId = $this->input->post('realm');
        $CostBarGold = $this->input->post('cost');
        $GoldCharacter = $this->data_model->getmoneychar($realmId, $characterGuid);

        if (!$this->CheckCharOnline($characterGuid, $realmId)) {
            die(long("LongCharacterOnline", "goldtodp"));
        }

        if ($CostBarGold <= 1000) {
            die(long("conversionbelow", "goldtodp"));
        }

        if ($CostBarGold > $GoldCharacter) {
            die(long("Gold_Character_not_allowed", "goldtodp"));
        } else {
            if ($Price != 0) {
                if ($PriceCurrency == 'dp') {
                    if ($this->user->getDp() < $Price) {
                        die($this->lang('ERROR_PRICE_DP', 'goldtodp'));
                    }
                }
                $this->user->setDp($this->user->getDp() - $Price);
            }

            $DPCalc = ($CostBarGold / $this->config->item('calc'));
            if ($this->ChangeGoldCharacter($characterGuid, $realmId, $CostBarGold))    ///// BUG Gold Number
            {
                $this->user->setDp($this->user->getDp() + $DPCalc);

                die("1");
            } else {
                die("Error");
            }
        }

    }

    public function CheckCharOnline($guid, $realmid)
    {
        $character_database = $this->realms->getRealm($realmid)->getCharacters();
        $character_database->connect();
        $query = $character_database->getConnection()->query("SELECT * FROM characters WHERE guid = ? and  online=0", array($guid));

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function ChangeGoldCharacter($guid, $realmid, $money)
    {
        $money = $money * 10000;
        $character_database = $this->realms->getRealm($realmid)->getCharacters();
        $character_database->connect();
        $query = $character_database->getConnection()->query("UPDATE characters SET money = money - '$money' WHERE guid = ?", array($guid));

        if ($query)
            return true;
        else
            return false;
    }
}
