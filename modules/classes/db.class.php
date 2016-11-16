<?php
class DB_class 
{
	private $db_host,$db_name,$db_user,$db_pass,$db;
	function __construct($db_host,$db_name,$db_user,$db_pass)
	{
		if (!$this->db) {
			$con = @ new mysqli($db_host, $db_user, $db_pass, $db_name);
			if (!$con->connect_error) {
				$this->db = true;
				$con->set_charset("utf8");
				$this->con = $con;
				return true;
			}else{
				return false;
			}
		}
	}

	function select($while,$select,$from,$where = null,$order = null)
	{
		if ($where != NULL) {
			$where = "WHERE ".$where;
		}
		if ($select == "*") {
			$select_array = explode(",", "id,login,email,password,session_key,vk_key,fb_key,twit_key,inst_key,avatar,reg_date,enter_date,count_news,mobile_token");
		}elseif ($select == "mobile_info") {
			$select = "id,login,mobile_token";
			$select_array = explode(",", $select);
		}elseif ($select == "news_info") {
			$select = "vk_key,fb_key,twit_key,inst_key,count_news";
			$select_array = explode(",", $select);
		}else{
			$select_array = explode(",", $select);
		}
		$count_select = count($select_array);
		$sql = "SELECT ".$select." FROM `".$from."` ".$where."";
		$u_query = $this->con->query($sql);
		$count_row = 0;
		if ($u_query->num_rows != 0) {
			while ($query_row = $u_query->fetch_array(MYSQLI_ASSOC)) {
				$select_count = 0;
				while ($select_count < $count_select) {
					if ($while == true) {
						$return_array[$count_row][$select_array[$select_count]] = $query_row[$select_array[$select_count]];
						$select_count++;
					}else{
						$return_array[$select_array[$select_count]] = $query_row[$select_array[$select_count]];
						$select_count++;
					}
				}
				$count_row++;
			}
		}else{
			$return_array = 0;
		}
		return $return_array;
	}

	function update($from,$set,$where)
	{
		if ($where != NULL) {
			$where = "WHERE ".$where;
		}
		$update_sql = "UPDATE ".$from." SET ".$set." ".$where."";
		$update_query = $this->con->query($update_sql);
	}

	function insert($from,$insert,$values)
	{
		$insert_sql = "INSERT INTO ".$from." (".$insert.") VALUES (".$values.")";
		$insert_query = $this->con->query($insert_sql);
	}

	function __destruct()
	{
		mysqli_close($this->con);
		$this->db = false;
	}
}
?>