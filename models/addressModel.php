<?php
namespace Models;
class addressModel{
	private $db,$permission;
	public $idaddress,$idfather,$name,$status;
	public function __construct(){
		$this->db = new \core\ameliaBD;
		$this->permission = new \models\permissionModel;
	}
	public function listt($draw,$search,$start,$length,$controller){
		$start = (empty($start))? 0 : $start;
		$length = (empty($length))? 10 : $length;
		if($controller == "country"){
			$sql1 = "SELECT c.idaddress,c.name,c.status FROM taddress c 
			WHERE (c.idaddress LIKE '%$search%' OR c.name LIKE '%$search%') AND c.idfather='0' ORDER BY c.idaddress DESC LIMIT $start,$length ";
			$sql2 = "SELECT count(*) FROM taddress c ";
			$sql3 = "SELECT count(*) FROM taddress c 
			WHERE (c.idaddress LIKE '%$search%' OR c.name LIKE '%$search%') AND c.idfather='0' LIMIT $start,$length";
		}else if($controller == "state"){
			$sql1 = "SELECT s.idaddress,s.name,c.name as father,s.status FROM taddress c 
			INNER JOIN taddress s ON c.idaddress=s.idfather
			WHERE (s.idaddress LIKE '%$search%' OR s.name LIKE '%$search%' OR c.name LIKE '%$search%') AND c.idfather='0' ORDER BY s.idaddress DESC LIMIT $start,$length ";
			$sql2 = "SELECT count(*) FROM taddress c 
			INNER JOIN taddress s ON c.idaddress=s.idfather";
			$sql3 = "SELECT count(*) FROM taddress c 
			INNER JOIN taddress s ON c.idaddress=s.idfather
			WHERE (s.idaddress LIKE '%$search%' OR s.name LIKE '%$search%' OR c.name LIKE '%$search%') AND c.idfather='0' LIMIT $start,$length";
		}else if($controller == "municipality"){
			$sql1 = "SELECT m.idaddress,m.name,s.name as father,m.status FROM taddress c 
			INNER JOIN taddress s ON c.idaddress=s.idfather
			INNER JOIN taddress m ON s.idaddress=m.idfather
			WHERE (m.idaddress LIKE '%$search%' OR m.name LIKE '%$search%' OR s.name LIKE '%$search%') AND c.idfather='0' ORDER BY m.idaddress DESC LIMIT $start,$length ";
			$sql2 = "SELECT count(*) FROM taddress c 
					INNER JOIN taddress s ON c.idaddress=s.idfather
					INNER JOIN taddress m ON s.idaddress=m.idfather";
			$sql3 = "SELECT count(*) FROM taddress c 
					INNER JOIN taddress s ON c.idaddress=s.idfather
					INNER JOIN taddress m ON s.idaddress=m.idfather
					WHERE (m.idaddress LIKE '%$search%' OR m.name LIKE '%$search%' OR s.name LIKE '%$search%') AND c.idfather='0' LIMIT $start,$length";
		}else if($controller == "parish"){
			$sql1 = "SELECT p.idaddress,p.name,m.name as father,p.status FROM taddress c 
					INNER JOIN taddress s ON c.idaddress=s.idfather
					INNER JOIN taddress m ON s.idaddress=m.idfather
					INNER JOIN taddress p ON m.idaddress=p.idfather WHERE (p.idaddress LIKE '%$search%' OR p.name LIKE '%$search%' OR m.name LIKE '%$search%') AND c.idfather='0' ORDER BY p.idaddress DESC LIMIT $start,$length ";
			$sql2 = "SELECT count(*) FROM taddress c 
					INNER JOIN taddress s ON c.idaddress=s.idfather
					INNER JOIN taddress m ON s.idaddress=m.idfather
					INNER JOIN taddress p ON m.idaddress=p.idfather";
			$sql3 = "SELECT count(*) FROM taddress c 
					INNER JOIN taddress s ON c.idaddress=s.idfather
					INNER JOIN taddress m ON s.idaddress=m.idfather
					INNER JOIN taddress p ON m.idaddress=p.idfather WHERE (p.idaddress LIKE '%$search%' OR p.name LIKE '%$search%' OR m.name LIKE '%$search%') AND c.idfather='0' LIMIT $start,$length";
		}
		$this->db->prepare($sql1);
		$a1 = $this->db->execute();
		while($b = $a1->fetchAll()){ $c = $b; }
		$d["data"]="";
		foreach ($c as $key => $val) {
			$d["data"][$key]["idaddress"] = $val["idaddress"];
			$d["data"][$key]["name"] = $val["name"];
			$d["data"][$key]["father"] = $val["father"];
			$d["data"][$key]["btn"] = $this->permission->getpermission($val["idaddress"],$val["status"]);
		}
		$this->db->prepare($sql2);
		$a2 = $this->db->execute();
		while($b2 = $a2->fetchAll()){ $c2 = $b2; }
		$d["draw"] = $draw;
		$d["recordsTotal"] = $c2[0][0];
		$this->db->prepare($sql3);
		$a3 = $this->db->execute();
		while($b3 = $a3->fetchAll()){ $c3 = $b3; }
		$d["recordsFiltered"] = $c3[0][0];
		return $d;
	}
	public function dependencies(){
		$dependencies["add"] = $this->permission->getpermissionadd();
		return $dependencies;
	}
	public function add(){
		$this->db->prepare("INSERT INTO taddress (idfather,name,status) VALUES (?,?,'1');");
		return $this->db->execute(array($this->idfather,$this->name));
	}
	public function query(){
		$this->db->prepare("SELECT * FROM taddress WHERE idaddress=? ;");
		$data=$this->db->execute(array($this->idaddress));
		foreach ($data as $val) { $d=$val; }
		return $d;
	}
	public function edit(){
		$this->db->prepare("UPDATE taddress SET idfather=?,name=? WHERE idaddress=?;");
		return $this->db->execute(array($this->idfather,$this->name,$this->idaddress));
	}
	public function delete(){
		$this->db->prepare("DELETE FROM taddress WHERE idaddress=?;");
		return $this->db->execute(array($this->idaddress));
	}
	public function status($num){
		$this->db->prepare("UPDATE taddress SET status=? WHERE idaddress=?;");
		return $this->db->execute(array($num,$this->idaddress));
	}
	public function pdf_country(){
		$this->db->prepare("SELECT c.idaddress,c.name,c.status FROM taddress c 
			WHERE c.idfather='0' ORDER BY 1 DESC ");
		$data=$this->db->execute();
		foreach ($data as $val) { $d[]=$val; }
		return $d;
	}
	public function pdf_state(){
		$this->db->prepare("SELECT s.idaddress,s.name,c.name as father,s.status FROM taddress c 
			INNER JOIN taddress s ON c.idaddress=s.idfather
			WHERE c.idfather='0' ORDER BY 1 DESC ");
		$data=$this->db->execute();
		foreach ($data as $val) { $d[]=$val; }
		return $d;
	}
	public function pdf_municipality(){
		$this->db->prepare("SELECT m.idaddress,m.name,s.name as father,m.status FROM taddress c 
			INNER JOIN taddress s ON c.idaddress=s.idfather
			INNER JOIN taddress m ON s.idaddress=m.idfather
			WHERE c.idfather='0' ORDER BY 1 DESC ");
		$data=$this->db->execute();
		foreach ($data as $val) { $d[]=$val; }
		return $d;
	}
	public function pdf_parish(){
		$this->db->prepare("SELECT p.idaddress,p.name,m.name as father,p.status FROM taddress c 
					INNER JOIN taddress s ON c.idaddress=s.idfather
					INNER JOIN taddress m ON s.idaddress=m.idfather
					INNER JOIN taddress p ON m.idaddress=p.idfather WHERE c.idfather='0' ORDER BY 1 DESC ");
		$data=$this->db->execute();
		foreach ($data as $val) { $d[]=$val; }
		return $d;
	}
	public function search($value){
		$this->db->prepare("SELECT p.idaddress,CONCAT(p.name,' - ',m.name,' - ',s.name,' - ',c.name) FROM taddress p INNER JOIN taddress m ON p.idfather=m.idaddress INNER JOIN taddress s ON m.idfather=s.idaddress INNER JOIN taddress c ON s.idfather=c.idaddress WHERE (p.name LIKE '%$value%' OR m.name LIKE '%$value%') AND p.status='1' AND m.status='1' AND s.status='1' AND c.status='1' ;");
		$data=$this->db->execute();
		foreach ($data as $val) { $d[]=$val; }
		return $d;
	}
	public function search_c($value){
		$this->db->prepare("SELECT c.idaddress,c.name FROM taddress p INNER JOIN taddress m ON p.idfather=m.idaddress INNER JOIN taddress s ON m.idfather=s.idaddress INNER JOIN taddress c ON s.idfather=c.idaddress WHERE c.name LIKE '%$value%' AND c.status='1' ;");
		$data=$this->db->execute();
		foreach ($data as $val) { $d[]=$val; }
		return $d;
	}
	public function search_s($value){
		$this->db->prepare("SELECT s.idaddress,CONCAT(s.name,' - ',c.name) FROM taddress p INNER JOIN taddress m ON p.idfather=m.idaddress INNER JOIN taddress s ON m.idfather=s.idaddress INNER JOIN taddress c ON s.idfather=c.idaddress WHERE s.name LIKE '%$value%' AND s.status='1' AND c.status='1' ;");
		$data=$this->db->execute();
		foreach ($data as $val) { $d[]=$val; }
		return $d;
	}
	public function search_m($value){
		$this->db->prepare("SELECT m.idaddress,CONCAT(m.name,' - ',s.name,' - ',c.name) FROM taddress p INNER JOIN taddress m ON p.idfather=m.idaddress INNER JOIN taddress s ON m.idfather=s.idaddress INNER JOIN taddress c ON s.idfather=c.idaddress WHERE m.name LIKE '%$value%' AND m.status='1' AND s.status='1' AND c.status='1' ;");
		$data=$this->db->execute();
		foreach ($data as $val) { $d[]=$val; }
		return $d;
	}
}
?>