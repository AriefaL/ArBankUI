<?php 

namespace AriefaL\arbankui\forms;
    
use AriefaL\arbankui\Main;
use AriefaL\arbankui\libs\form_by_jojoe\SimpleForm;
use AriefaL\arbankui\libs\form_by_jojoe\CustomForm;

use pocketmine\utils\TextFormat;
use pocketmine\Player;
    
class BankForm {
    
    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
	
	/* Form Create Akses Bank */
    public function createForm($player){
		$form = new CustomForm(function (Player $player, $data) {
            if ($data === null) {
                return true;
            }
			
			if(isset($data[1])) {
				if(isset($data[2])) {
					if(is_numeric($data[2])) {
						if(!$this->plugin->bank->exists($player->getName())){
							$this->plugin->bank->setNested($player->getName().".Name", $data[1]);
							$this->plugin->bank->setNested($player->getName().".Rekening", $data[2] * 30);
							$this->plugin->bank->setNested($player->getName().".Pin", $data[2]);
							$this->plugin->bank->setNested($player->getName().".Money", "0");
							$this->plugin->bank->save();
							$this->successCreateForm($player);
						}
					}else{
						$player->sendMessage(Main::PREFIX."§c[ERROR] Pin harus type number");
					}
				}else{
					$player->sendMessage(Main::PREFIX."§c[ERROR] Mohon isi data tersebut!");
				}
			}else{
				$player->sendMessage(Main::PREFIX."§c[ERROR] Mohon isi data tersebut!");
			}
			
        });
        $form->setTitle("§lMembuat ATM");
        $form->addLabel("§fHallo, ".$player->getName()." Selamat datang.\n".
						"§fAnda belum mempunyai ATM\n".
						"§fApakah ingin membuatnya?");
        $form->addInput("\n§fMasukan Atas Nama Anda di sini.", "§7Type string", $player->getName());
        $form->addInput("\n§fMasukan Pin ATM Anda.", "§7Pin maksimal 6 digit");
        $form->sendToPlayer($player);
		return $form;
	}
	
	/* Form Success Create Bank */
    public function successCreateForm($player){
		$form = new SimpleForm(function (Player $player, $data) {
        $result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
					$player->sendMessage(Main::PREFIX."§aTerimasih ".$player->getName());
				break;
			}
		});
		$form->setTitle("§lMembuat ATM");
		$form->setContent("§l§6====== §bSUCCESS MEMBUAT §6======\n".
						  "§r§fSekarang Anda sudah mempunyai akses untuk BankUI kami. Yang dapat Anda lakukan di BankUI kamu adalah:\n".
						  "§r§g - Menabung Uang Anda dalam jumlah berapapun,\n".
						  "§r§g - Mengambil Uang ditabungan Anda,\n".
						  "§r§g - Mentransfer Uang kepada Player lain.\n \n".
						  "§r§fInformasi Bank Anda:\n".
						  "§r§g - Atas Nama: ".$this->plugin->bank->getNested($player->getName().".Name")."\n".
						  "§r§g - No.Rekening: ".$this->plugin->bank->getNested($player->getName().".Rekening")."\n".
						  "§r§g - No.Pin: ".$this->plugin->bank->getNested($player->getName().".Pin")."\n".
						  "§r§g - Uang Tabungan: ".$this->plugin->bank->getNested($player->getName().".Money")."\n \n".
						  "§r§fBaik, Terimakasih telah mempercayai BankUI kami.");
		$form->addButton("§l§aTerima",0,"textures/ui/confirm", 0);
		$form->sendToPlayer($player);
		return $form;
	}
	
	/* Form Select Menu Bank */
    public function menuForm($player){
		$form = new SimpleForm(function (Player $player, $data) {
        $result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
					$this->infobankForm($player);
				break;
				case 1:
					$this->transferBankForm($player);
				break;
				case 2:
					$this->takeBankForm($player);
				break;
				case 3:
					$this->transfeMoneyForm($player);
				break;
				case 4:
					// Notting, you can edit message to close.
				break;
			}
		});
		$form->setTitle("§lMenu Bank");
		$form->setContent("§fPilih salah satu untuk transaksi:");
		/* Menu Informasi */
		$form->addButton("§lInformasi Banking\n§r§aKlik untuk membuka",0,"textures/items/book_portfolio", 0);
		/* Menu Transfer or Transaction money in your Bank*/
		$form->addButton("§lTabung uang dalam Bank\n§r§aUang di Bank: §2".$this->plugin->bank->getNested($player->getName().".Money"),0,"textures/ui/icon_best3", 1);
		/* Menu Take your Money in Bank */
		$form->addButton("§lAmbil uang dalam Bank\n§r§aKlik untuk proses",0,"textures/ui/MCoin", 2);
		/* Menu Pay or Give you money to players */
		$form->addButton("§lKirim uang ke pemain\n§r§aKlik untuk proses",0,"textures/ui/icon_import", 3);
		/* Menu Close */
		$form->addButton("§l§cKeluar",0,"textures/ui/cancel", 4);
		$form->sendToPlayer($player);
		return $form;
	}
	
	/* Form Informasi Banking */
    public function infobankForm($player){
		$form = new SimpleForm(function (Player $player, $data) {
        $result = $data;
			if($result === null){
				return true;
			}
			switch($result){
				case 0:
					$this->menuForm($player);
				break;
			}
		});
		$form->setTitle("§lBank UI");
		$form->setContent("§l§6===== §bInformasi Banking §6=====\n \n".
						  "§r§g - Atas Nama: §f".$this->plugin->bank->getNested($player->getName().".Name")."\n".
						  "§r§g - No.Rekening: §f".$this->plugin->bank->getNested($player->getName().".Rekening")."\n".
						  "§r§g - No.Pin: §f".$this->plugin->bank->getNested($player->getName().".Pin")."\n".
						  "§r§g - Uang Tabungan: §f".$this->plugin->bank->getNested($player->getName().".Money")."\n \n".
						  "§r§fIni adalah info Banking Anda agar memudahkan proses kerja dalam menggunakan BankUI kami.\n".
						  "§r§fTerimakasih telah mempercayai Proses kami di BankUI ini.");
		$form->addButton("§l§aConfirm",0,"textures/ui/confirm", 0);
		$form->sendToPlayer($player);
		return $form;
	}
	
	/* Menu Transfer or Transaction money in your Bank*/
	public function transferBankForm($player){
        $form = new CustomForm(function (Player $player, $data) {
            if ($data === null) {
                return true;
            }
			
			$dataName = $this->plugin->bank->getNested($player->getName().".Name");
			$dataPin = $this->plugin->bank->getNested($player->getName().".Pin");
			$dataMoney = $this->plugin->bank->getNested($player->getName().".Money");
			if(isset($data[1]) && $data[1] == $dataName) {
				if(is_numeric($data[2]) && $data[2] == $dataPin){
					if(is_numeric($data[3])){
						if(!$this->plugin->Economy->myMoney($player) == 0){
							$this->plugin->bank->setNested($player->getName().".Money", $dataMoney + $data[3]);
							$this->plugin->Economy->reduceMoney($player, $data[3]); 
							$this->plugin->bank->save();
							$player->sendMessage(Main::PREFIX."§aTelah berhasil menabung uang Anda sebesar: ".$data[3]);
						}else{
							$player->sendMessage(Main::PREFIX."§cMaaf, Anda tidak memiliki uang");
						}
					}else{
						$player->sendMessage(Main::PREFIX."§c[ERROR] Nominal type number");
					}
				}else{
					$player->sendMessage(Main::PREFIX."§c[ERROR] Pin Anda salah");
				}
			}else{
				$player->sendMessage(Main::PREFIX."§cAtas Nama: ".$data[1].". adalah bukan milik Anda!");
			}
			
        });
        $form->setTitle("§lMenabung Uang");
        $form->addLabel("§fHallo, ".$player->getName()." Selamat datang di BankUI\n".
						"§fIngin menabung uang Anda ke Bank kami?\n".
						"§fAnda sekarang memiliki uang sebanyak: ".$this->plugin->Economy->myMoney($player));
        $form->addInput("\n§fMasukan Atas Nama Anda di sini.", "§7Type string");
        $form->addInput("\n§fMasukan Pin Anda di sini.", "§7Type string");
        $form->addInput("\n§fMasukan Nominal yang akan ditabung.", "§7Type string");
        $form->sendToPlayer($player);
		return $form;
    }
	
	/* Menu Take your Money in Bank */
	public function takeBankForm($player){
        $form = new CustomForm(function (Player $player, $data) {
            if ($data === null) {
                return true;
            }
			
            $dataName = $this->plugin->bank->getNested($player->getName().".Name");
            $dataPin = $this->plugin->bank->getNested($player->getName().".Pin");
            $dataMoney = $this->plugin->bank->getNested($player->getName().".Money");
			if(isset($data[1]) && $data[1] == $dataName) {
				if(is_numeric($data[2]) && $data[2] == $dataPin){
					if(is_numeric($data[3])){
						if(!$dataMoney == 0){
							$this->plugin->bank->setNested($player->getName().".Money", $dataMoney - $data[3]);
							$this->plugin->Economy->addMoney($player, $data[3]); 
							$this->plugin->bank->save();
							$player->sendMessage(Main::PREFIX."§aTelah berhasil mengambil uang Anda sebesar: ".$data[3]);
						}else{
							$player->sendMessage(Main::PREFIX."§cMaaf, Anda tidak memiliki uang ditabungan");
						}
					}else{
						$player->sendMessage(Main::PREFIX."§c[ERROR] Nominal type number");
					}
				}else{
					$player->sendMessage(Main::PREFIX."§c[ERROR] Pin Anda salah");
				}
			}else{
				$player->sendMessage(Main::PREFIX."§cAtas Nama: ".$data[1].". adalah bukan milik Anda!");
            }
			
        });
        $form->setTitle("§lMengambil Uang");
        $form->addLabel("§fHallo, ".$player->getName()." Selamat datang di BankUI\n".
						"§fIngin megambil uang Anda di Bank kami?\n".
						"§fUang Anda di Bank kami sebanyak: ".$this->plugin->bank->getNested($player->getName().".Money"));
        $form->addInput("\n§fMasukan Atas Nama Anda di sini.", "§7Type string");
        $form->addInput("\n§fMasukan Pin Anda di sini.", "§7Type string");
        $form->addInput("\n§fMasukan Nominal yang akan diambil.", "§7Type string");
        $form->sendToPlayer($player);
		return $form;
    }
	
	/* Menu Pay or Give you money to players */
	public function transfeMoneyForm($player){
		$form = new CustomForm(function (Player $player, $data) {
			if($data === null){
				return true;
			}
			
			$target = $this->plugin->getServer()->getPlayer($data[1]);
			if($target instanceof Player) {
				$targetName = $this->plugin->bank->getNested($target->getName().".Name");
				$targetRek = $this->plugin->bank->getNested($target->getName().".Rekening");
				$targetMoney = $this->plugin->bank->getNested($target->getName().".Money");
			
				$playerMoney = $this->plugin->bank->getNested($player->getName().".Money");
				$playerPin = $this->plugin->bank->getNested($player->getName().".Pin");
				if(isset($data[2]) && $data[2] == $targetName) {
					if(is_numeric($data[3]) && $data[3] == $targetRek){
						if(is_numeric($data[4]) && $data[4] == $playerPin) {
							if(is_numeric($data[5])){
								if(!$playerMoney == 0){
									$this->plugin->bank->setNested($target->getName().".Money", $targetMoney + $data[5]);
									$this->plugin->bank->setNested($player->getName().".Money", $playerMoney - $data[5]); 
									$this->plugin->bank->save();
									$player->sendMessage(Main::PREFIX."§aTelah berhasil mengirim uang Anda kepada ".$target->getName().". sebesar: ".$data[5]);
								}else{
									$player->sendMessage(Main::PREFIX."§cMaaf, Anda tidak memiliki uang ditabungan");
								}
							}else{
								$player->sendMessage(Main::PREFIX."§c[ERROR] Nominal harus type number");
							}
						}else{
							$player->sendMessage(Main::PREFIX."§c[ERROR] Pin Anda salah");
						}
					}else{
						$player->sendMessage(Main::PREFIX."§c[ERROR] Rekening yang akan dikirim salah!");
					}
				}else{
					$player->sendMessage(Main::PREFIX."§cAtas Nama: ".$data[2].". tidak ada!");
				}
			}else{
				$player->sendMessage(Main::PREFIX."§cAtas Nama: ".$data[1].". tidak ada!");
			}
			
		});
		$form->setTitle("§lTransfer Uang");
		$form->addLabel("§fHallo, ".$player->getName()." Selamat datang di BankUI\n".
						"§fDisini bisa mentrasfer uang Anda kepada Player lain\n".
						"§fAnda yakin memberikan uang kepada player lain?");
        $form->addInput("\n§fPlayer yang dituju.", "§7Type string");
        $form->addInput("\n§fMasukan Atas Nama yang mau diberi.", "§7Type string");
        $form->addInput("\n§fMasukan Rekening yang mau diberi.", "§7Type string");
        $form->addInput("\n§fMasukan Pin Anda.", "§7Type string");
        $form->addInput("\n§fMasukan Nominal.", "§7Type string");
		$form->sendToPlayer($player);
		return $form;
    }
}