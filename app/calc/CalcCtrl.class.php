<?php
// W skrypcie definicji kontrolera nie trzeba dołączać problematycznego skryptu config.php,
// ponieważ będzie on użyty w miejscach, gdzie config.php zostanie już wywołany.

require_once $conf->root_path.'/lib/smarty/Smarty.class.php';
require_once $conf->root_path.'/lib/Messages.class.php';
require_once $conf->root_path.'/app/calc/CalcForm.class.php';
require_once $conf->root_path.'/app/calc/CalcResult.class.php';

/** 
 * Kontroler kalkulatora
 **/
class CalcCtrl {

	private $msgs;   //wiadomości dla widoku
        private $infos;  //informacje dla widoku
	private $form;   //dane formularza (do obliczeń i dla widoku)
	private $result; //inne dane dla widoku
	private $hide_intro; //zmienna informująca o tym czy schować intro

	/** 
	 * Konstruktor - inicjalizacja właściwości
	 */
	public function __construct(){
		//stworzenie potrzebnych obiektów
		$this->msgs = new Messages();
		$this->form = new CalcForm();
		$this->result = new CalcResult();
		$this->hide_intro = false;
	}
	
	/** 
	 * Pobranie parametrów
	 */
	public function getParams(){
                $this->form->kwota = isset($_REQUEST ['kw']) ? $_REQUEST ['kw'] : null;
                $this->form->okres = isset($_REQUEST ['ok']) ? $_REQUEST ['ok'] : null;
                $this->form->oprocentowanie = isset($_REQUEST ['op']) ? $_REQUEST ['op'] : null;
	}
	
	/** 
	 * Walidacja parametrów
	 * @return true jeśli brak błedów, false w przeciwnym wypadku 
	 */
	public function validate() {
                // sprawdzenie, czy parametry zostały przekazane
                if (! (isset ( $this->form->kwota ) && isset ( $this->form->okres ) && isset ( $this->form->oprocentowanie ))) {
                        // sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
                    return false; //zakończ walidację z błędem
                }

                // sprawdzenie, czy potrzebne wartości zostały przekazane
                if ($this->form->kwota == "") {
                    $this->msgs->addError('Nie podano kwoty kredytu.');
                }
                if ($this->form->okres == "") {
                    $this->msgs->addError('Nie podano okresu.');
                }
                if ($this->form->oprocentowanie == "") {
                    $this->msgs->addError('Nie podano oprocentowania.');
                }

                // nie ma sensu walidować dalej gdy brak parametrów
                if (! $this->msgs->isError()) {
                    if (! is_numeric ( $this->form->kwota )) {
                        $this->msgs->addError('Kwota nie jest liczbą.');
                    }
                    if (! is_numeric ( $this->form->okres )) {
                        $this->msgs->addError('Okres nie jest liczbą.');
                    }
                    if (! is_numeric ( $this->form->oprocentowanie )) {
                        $this->msgs->addError('Oprocentowanie nie jest liczbą.');
                    }
                }

                return ! $this->msgs->isError();
	}
	
	/** 
	 * Pobranie wartości, walidacja, obliczenie i wyświetlenie
	 */
	public function process(){

		$this->getparams();
		
		if ($this->validate()) {
				
                    $this->form->kwota = floatval($this->form->kwota);
                    $this->form->okres = intval($this->form->okres);
                    $this->form->oprocentowanie = floatval($this->form->oprocentowanie);
                    $this->msgs->addInfo('Parametry poprawne.');

                    $this->result->result = floatval($this->form->kwota/($this->form->okres*12))+(($this->form->kwota/($this->form->okres*12))*($this->form->oprocentowanie/100));

                    $this->msgs->addInfo('Wykonano obliczenia.');
		}
		
		$this->generateView();
	}
	
	
	/**
	 * Wygenerowanie widoku
	 */
	public function generateView(){
		global $conf;
		
		$smarty = new Smarty();
		$smarty->assign('conf',$conf);
		
		$smarty->assign('page_title','Kalkulator Kredytowy');
		$smarty->assign('page_header','Kontroler główny');
				
		$smarty->assign('hide_intro',$this->hide_intro);
		
		$smarty->assign('msgs',$this->msgs);
		$smarty->assign('form',$this->form);
		$smarty->assign('res',$this->result);
		
		$smarty->display($conf->root_path.'/app/calc/CalcView.tpl');
	}
}
