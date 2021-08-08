<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class AdGuardHome extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */

	public static function dependancy_info() {
		$return = array();
        $return['log'] = __CLASS__ . '_update';
        $return['progress_file'] = jeedom::getTmpFolder('AdGuardHome') . '/dependance';
		$return['state'] = 'ok';
        if (exec('pip3 list installed | grep adguardhome | wc -l') == 0){
			$return['state'] = 'nok';
        }

		return $return;
    }
    
    public static function dependancy_install() {
        log::remove(__CLASS__ . '_update');
        return array('script' => dirname(__FILE__) . '/../../resources/install.sh ' . jeedom::getTmpFolder('AdGuardHome') . '/dependance', 'log' => log::getPathToLog(__CLASS__ . '_update'));
    }

    public static function cron() {
            self::ExecuteCron();
    }

    private static function ExecuteCron(){
        foreach (self::byType('AdGuardHome') as $eqLogic) 
        {	
            if ($eqLogic->getIsEnable() == 1) 
            {
                $eqLogic->updateData();
            }
        }
    }

    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
        $refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new AdGuardHomeCmd();
			$refresh->setName(__('Rafraichir', __FILE__));
        }
        
		$refresh->setEqLogic_id($this->getId());
		$refresh->setLogicalId('refresh');
		$refresh->setType('action');
        $refresh->setSubType('other');
        $refresh->save();

        $state = $this->getCmd(null, 'state');
		if (!is_object($state)) {
			$state = new AdGuardHomeCmd();
			$state->setName(__('Etat', __FILE__));
        }
        
		$state->setEqLogic_id($this->getId());
		$state->setLogicalId('state');
		$state->setType('info');
        $state->setSubType('binary');
        $state->setIsVisible(0);
        $state->setIsHistorized(1);
        $state->setTemplate('dashboard','prise');
        $state->setTemplate('mobile','prise');
        $state->setDisplay("generic_type","ENERGY_STATE");
        $state->save();

        $on = $this->getCmd(null, 'on');
		if (!is_object($on)) {
			$on = new AdGuardHomeCmd();
			$on->setName(__('On', __FILE__));
        }
        
		$on->setEqLogic_id($this->getId());
		$on->setLogicalId('on');
		$on->setType('action');
        $on->setSubType('other');
        $on->setIsVisible(1);
        $on->setValue($state->getId());
        $on->setTemplate('dashboard','prise');
        $on->setTemplate('mobile','prise');
        $on->setDisplay("generic_type","ENERGY_ON");
        $on->save();

        $off = $this->getCmd(null, 'off');
		if (!is_object($off)) {
			$off = new AdGuardHomeCmd();
			$off->setName(__('Off', __FILE__));
        }
        
		$off->setEqLogic_id($this->getId());
		$off->setLogicalId('off');
		$off->setType('action');
        $off->setSubType('other');
        $off->setIsVisible(1);
        $off->setValue($state->getId());
        $off->setTemplate('dashboard','prise');
        $off->setTemplate('mobile','prise');
        $off->setDisplay("generic_type","ENERGY_OFF");
        $off->save();

        // Values
        $processingTime = $this->getCmd(null, 'processingTime');
		if (!is_object($processingTime)) {
			$processingTime = new AdGuardHomeCmd();
			$processingTime->setName(__('Processing Time', __FILE__));
        }
        
		$processingTime->setEqLogic_id($this->getId());
		$processingTime->setLogicalId('processingTime');
		$processingTime->setType('info');
        $processingTime->setSubType('numeric');
        $processingTime->setIsHistorized(1);
        $processingTime->setIsVisible(1);
        $processingTime->setUnite('ms');
        $processingTime->save();

        $dnsQueries = $this->getCmd(null, 'dnsQueries');
		if (!is_object($dnsQueries)) {
			$dnsQueries = new AdGuardHomeCmd();
			$dnsQueries->setName(__('DNS Queries', __FILE__));
        }
        
		$dnsQueries->setEqLogic_id($this->getId());
		$dnsQueries->setLogicalId('dnsQueries');
		$dnsQueries->setType('info');
        $dnsQueries->setSubType('numeric');
        $dnsQueries->setIsHistorized(1);
        $dnsQueries->setIsVisible(1);
        $dnsQueries->setConfiguration('maxValue', $this->getConfiguration("LimitByDay"));
        $dnsQueries->save();

        $nbrBlockedFiltering = $this->getCmd(null, 'nbrBlockedFiltering');
		if (!is_object($nbrBlockedFiltering)) {
			$nbrBlockedFiltering = new AdGuardHomeCmd();
			$nbrBlockedFiltering->setName(__('DNS Queries Blocked', __FILE__));
        }
        
		$nbrBlockedFiltering->setEqLogic_id($this->getId());
		$nbrBlockedFiltering->setLogicalId('nbrBlockedFiltering');
		$nbrBlockedFiltering->setType('info');
        $nbrBlockedFiltering->setSubType('numeric');
        $nbrBlockedFiltering->setIsHistorized(1);
        $nbrBlockedFiltering->setIsVisible(1);
        $nbrBlockedFiltering->setConfiguration('maxValue', $this->getConfiguration("LimitByDay"));
        $nbrBlockedFiltering->save();

        $nbrBlockedPercentage = $this->getCmd(null, 'nbrBlockedPercentage');
		if (!is_object($nbrBlockedPercentage)) {
			$nbrBlockedPercentage = new AdGuardHomeCmd();
			$nbrBlockedPercentage->setName(__('% Blocked', __FILE__));
        }
        
		$nbrBlockedPercentage->setEqLogic_id($this->getId());
		$nbrBlockedPercentage->setLogicalId('nbrBlockedPercentage');
		$nbrBlockedPercentage->setType('info');
        $nbrBlockedPercentage->setSubType('numeric');
        $nbrBlockedPercentage->setIsHistorized(1);
        $nbrBlockedPercentage->setIsVisible(1);
        $nbrBlockedPercentage->setConfiguration('maxValue', 100);
        $nbrBlockedPercentage->setUnite('%');
        $nbrBlockedPercentage->save();
    }

    public function preUpdate() {
        
    }

    public function postUpdate() {
        
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    public function updateData(){
        $cmd = 'sudo python3 '.dirname(__FILE__) . '/../../resources/stats.py '. $this->getConfiguration("IP") .' '. $this->getConfiguration("Port") .' '. $this->getConfiguration("Username") .' '. $this->getConfiguration("Password");
        $result = shell_exec($cmd);
        log::add('AdGuardHome', 'debug', $result);
        if($result != "Error"){
            $values = explode(";", $result);
            $stateCmd = $this->getCmd(null, 'state');
            $stateCmd->event($values[0]);
            $processingTimeCmd = $this->getCmd(null, 'processingTime');
            $processingTimeCmd->event($values[1]);
            $dnsQueriesCmd = $this->getCmd(null, 'dnsQueries');
            $dnsQueriesCmd->event($values[2]);
            $nbrBlockedFilteringCmd = $this->getCmd(null, 'nbrBlockedFiltering');
            $nbrBlockedFilteringCmd->event($values[3]);
            $nbrBlockedPercentageCmd = $this->getCmd(null, 'nbrBlockedPercentage');
            $nbrBlockedPercentageCmd->event(floor($values[4] * 100) / 100);
        }
    }

    public function setState($state){              
        $cmd = 'sudo python3 '.dirname(__FILE__) . '/../../resources/execute_cmd.py '. $this->getConfiguration("IP") .' '. $this->getConfiguration("Port") .' '. $this->getConfiguration("Username") .' '. $this->getConfiguration("Password") . ' '.$state;
        $result = shell_exec($cmd);
        if($result == "Done"){
            sleep(5);
            $this->updateData();
        }
    }
}

class AdGuardHomeCmd extends cmd {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */
    public function dontRemoveCmd() {
      return true;
    }

    public function execute($_options = array()) {
        $eqLogic = $this->getEqLogic();
        if ($this->getLogicalId() == 'refresh') {
			$eqLogic->updateData();
        }
        if ($this->getLogicalId() == 'on') {
            $eqLogic->setState(1);
        }
        if ($this->getLogicalId() == 'off') {
            $eqLogic->setState(0);
        }
    }
}

?>
