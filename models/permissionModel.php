<?php
namespace models;
class permissionModel{
	private $db;
	public $idcharge,$idservice,$idaction;
	public function __construct(){
		$this->db = new \core\ameliaBD;
	}
	public function generate_main(){
		$this->db->prepare("SELECT DISTINCT s.idservice,s.name,s.url,s.color,i.class,i.name as iname FROM tservice s INNER JOIN ticon i ON s.idicon=i.idicon INNER JOIN tservice s2 ON s.idservice=s2.idfather INNER JOIN tdcharge_service_action csa ON s2.idservice=csa.idservice WHERE s.idfather=0 AND csa.idcharge='".$_SESSION["idcharge"]."' AND s.status='1' ORDER BY s.ordered ASC;");
		$fathers = $this->db->execute();
		foreach ($fathers as $k => $father){
			$main[$k]["idservice"] = $father["idservice"];
			$main[$k]["name"] = $father["name"];
			$main[$k]["url"] = $father["url"];
			$main[$k]["color"] = $father["color"];
			$main[$k]["class"] = $father["class"];
			$main[$k]["iname"] = $father["iname"];
			$this->db->prepare("SELECT DISTINCT s.idservice,s.name,s.url,s.color,i.class,i.name as iname FROM tservice s INNER JOIN ticon i ON s.idicon=i.idicon INNER JOIN tdcharge_service_action csa ON s.idservice=csa.idservice INNER JOIN taction a ON csa.idaction=a.idaction WHERE s.idfather='".$father["idservice"]."' AND csa.idcharge='".$_SESSION["idcharge"]."' AND s.status='1' AND a.function<>'6' ORDER BY s.ordered ASC;");
			$childrens = $this->db->execute();
			foreach ($childrens as $k2 => $child){
				$main[$k]["childrens"][$k2]["idservice"] = $child["idservice"];
				$main[$k]["childrens"][$k2]["name"] = $child["name"];
				$main[$k]["childrens"][$k2]["url"] = $child["url"];
				$main[$k]["childrens"][$k2]["color"] = $child["color"];
				$main[$k]["childrens"][$k2]["class"] = $child["class"];
				$main[$k]["childrens"][$k2]["iname"] = $child["iname"];
				$this->db->prepare("SELECT s.idservice,s.name,s.url,s.color,i.class,i.name as iname FROM tservice s INNER JOIN ticon i ON s.idicon=i.idicon WHERE s.idfather='".$child["idservice"]."' AND s.status='1' ORDER BY s.ordered ASC;");
				$secondschildrens = $this->db->execute();
				foreach ($secondschildrens as $k3 => $secondchild) {
					$main[$k]["childrens"][$k2]["secondschildrens"][$k3]["idservice"] = $secondchild["idservice"];
					$main[$k]["childrens"][$k2]["secondschildrens"][$k3]["name"] = $secondchild["name"];
					$main[$k]["childrens"][$k2]["secondschildrens"][$k3]["url"] = $secondchild["url"];
					$main[$k]["childrens"][$k2]["secondschildrens"][$k3]["color"] = $secondchild["color"];
					$main[$k]["childrens"][$k2]["secondschildrens"][$k3]["class"] = $secondchild["class"];
					$main[$k]["childrens"][$k2]["secondschildrens"][$k3]["iname"] = $secondchild["iname"];
				}
			}
		}
		return $main;
	}
	public function getpermissionadd(){
		$this->db->prepare("SELECT a.name,a.function,i.class,i.name AS iname FROM tdcharge_service_action dcsa 
							INNER JOIN tservice s ON dcsa.idservice=s.idservice
							INNER JOIN taction a ON dcsa.idaction=a.idaction
							INNER JOIN ticon i ON a.idicon=i.idicon
							WHERE dcsa.idcharge='".$_SESSION['idcharge']."' AND s.url='".controller."'
							AND a.function='1' ORDER BY a.idaction DESC;");
		$q1 = $this->db->execute();
		$a = $q1->fetchAll();
		foreach ($a as $b){
			if( $b['function'] == '1' ){
				$btnadd ="<div class='row'><div class='col-md-3 col-md-offset-4'><a href='".url_base.controller."/add' class='btn1' data-toggle='tooltip' title='".permission_getpermissionadd_title1.$b['name'].permission_getpermissionadd_title2."'><i class='".$b['class']." ".$b['iname']."'></i> ".$b['name']."</a></div></div>";
			}
		}
		return $btnadd;
	}
	public function getpermission($id,$status='',$not_function=''){
		if(!empty($not_function)){
			$aux = " AND a.function<>'".$not_function."' ";
		}
		$this->db->prepare("SELECT a.name,a.function,i.class,i.name AS iname FROM tdcharge_service_action dcsa 
							INNER JOIN tservice s ON dcsa.idservice=s.idservice
							INNER JOIN taction a ON dcsa.idaction=a.idaction
							INNER JOIN ticon i ON a.idicon=i.idicon
							WHERE dcsa.idcharge='".$_SESSION['idcharge']."' AND s.url='".controller."'
							AND (a.function='2' OR a.function='3' OR a.function='4' OR a.function='5' OR a.function='7') ".$aux." ORDER BY a.idaction ASC;");
		$q1 = $this->db->execute();
		$a = $q1->fetchAll();
		$cell="<td>";
		foreach ($a as $b){
			if( $b['function'] == '4' && $status == '1' ){
				@$cell='<a href="'.url_base.controller.'/deactivate/'.$id.'" class="text-muted" data-toggle="tooltip" title="'.permission_getpermission_status_title.'"><i class="'.$b['class'].' '.$b['iname'].' text-success"></i><b>ACTIVO</b></a> ';
			}else if( $b['function'] == '5' && $status == '0' ){
				@$cell='<a href="'.url_base.controller.'/activate/'.$id.'" class="text-muted" data-toggle="tooltip" title="'.permission_getpermission_status_title.'"><i class="'.$b['class'].' '.$b['iname'].' text-danger"></i><b>INACTIVO</b></a> ';
			}else if( empty($cell) && $status != '2'){
				$val = ($status)? 'ACTIVO' : 'INACTIVO';
				@$cell='<b>'.$val.' </b>';
			}
		}		
		foreach ($a as $b) {
			if( $b['function'] == '2' ){
				@$cell.='<a href="'.url_base.controller.'/edit/'.$id.'" class="text-muted" data-toggle="tooltip" title="'.permission_getpermissionadd_title1.$b['name'].permission_getpermissionadd_title2.'"><i class="'.$b['class'].' '.$b['iname'].'"></i></a> ';
			}else if( $b['function'] == '3' ){
				@$cell.='<a href="'.url_base.controller.'/query/'.$id.'" class="text-muted" data-toggle="tooltip" title="'.permission_getpermissionadd_title1.$b['name'].permission_getpermissionadd_title2.'"><i class="'.$b['class'].' '.$b['iname'].'"></i></a> ';
			}else if( $b['function'] == '7'){
				@$cell.='<a href="'.url_base.controller.'/delete/'.$id.'" class="text-muted" data-toggle="tooltip" title="'.permission_getpermissionadd_title1.$b['name'].permission_getpermissionadd_title2.'" onclick="return confirm(\'Estas seguro que deseas eliminar este registro?\')"><i class="'.$b['class'].' '.$b['iname'].'"></i></a> ';
			}
		}
		$cell = ($cell != "<td>")? $cell : permission_getpermission_no_actions;
		$cell.="</td>";
		return $cell;
	}
	public function getpermission_two($id="",$status='',$not_function=''){
		if(!empty($not_function)){
			$aux = " AND a.function<>'".$not_function."' ";
		}
		$this->db->prepare("SELECT a.name,a.function,i.class,i.name AS iname FROM tdcharge_service_action dcsa 
							INNER JOIN tservice s ON dcsa.idservice=s.idservice
							INNER JOIN taction a ON dcsa.idaction=a.idaction
							INNER JOIN ticon i ON a.idicon=i.idicon
							WHERE dcsa.idcharge='".$_SESSION['idcharge']."' AND s.url='".controller."'
							AND (a.function='2' OR a.function='3' OR a.function='4' OR a.function='5' OR a.function='7') ".$aux." ORDER BY a.idaction ASC;");
		$this->db->execute();
		$a = $this->db->fetchAll();
		$arr="";
		foreach ($a as $k1 => $val) {
			$arr[$val["function"]] = $val["function"];
		}
		return $arr;
	}
	public function getpermission_action($function){
		if(!is_array($function)){
			$AND = " a.function='".$function."'";
		}else{
			$AND = " (a.function='".$function[0]."' OR  a.function='".$function[1]."' OR  a.function='".$function[2]."' OR  a.function='".$function[3]."' OR  a.function='".$function[4]."' OR  a.function='".$function[5]."' OR  a.function='".$function[6]."') ";
		}
		$this->db->prepare("SELECT a.function FROM taction a INNER JOIN tdcharge_service_action csa ON a.idaction=csa.idaction INNER JOIN tperson p ON csa.idcharge=p.idcharge INNER JOIN tservice s ON csa.idservice=s.idservice WHERE csa.idcharge=? AND s.url=? AND ".$AND.";");
		$this->db->execute(array($_SESSION["idcharge"],controller));
		$action = $this->db->fetchAll();
		if(empty($action[0][0])){
			$_SESSION["msj"] = no_permission;
			if($_SESSION["initiated"]=='1'){
				header('location: '.url_base.'home/dashboard');
			}else{
				header('location: '.url_base.'user/profile');
			}
			break;
		}else if($_SESSION["initiated"]=='0'){
			header('location: '.url_base.'user/profile');
		}
	}
}
?>