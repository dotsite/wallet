<?php

class MySQLCache{
   var $CachePath = _CACHE_PATH_;

   //Имя файла с информацией о пиковой нагрузке
   var $PeakFilename='!peak.txt';

   //Флаг, при установке которого ошибки запросов выводятся на экран
   var $Debug=true;

   //Флаг, указывающий, что данные выдаются из кэша
   var $FromCache=false;

   //Дата формирования данных
   var $DataDate=0;

   //Численный код ошибки выполнения последней операции с MySQL
   var $errno=0;

   //Строка ошибки последней операции с MySQL
   var $error='';

   //Информация о пиковой нагрузке
   var $Peak=array(
      0,    //Время выполнения
      '',   //Дата выполнения
      '',   //Запрос
      '',   //Вызвавший скрипт
   );

   var $NextRowNo=0;

   var $ResultData=array(
      'fields'=>array(),
      'data'=>array(),
   );

   function MySQLCache($query, $valid=10){
      if ($this->CachePath==''){
         $this->CachePath=dirname(__FILE__);
      }
      $query=trim($query);
      if (!@eregi('^SELECT', $query)){
         return mysql_query($query);
      }
      $filename=$this->CachePath.'/'.md5($query).'.txt';
      /* Попытка чтения кэш-файла */
      if ((@$file=fopen($filename, 'r')) && filemtime($filename)>(time()-$valid)){
         flock($file, LOCK_SH);
         $serial=file_get_contents($filename);
         $this->ResultData=unserialize($serial);
         $this->DataDate=filemtime($filename);
         $this->FromCache=true;
         fclose($file);
         return true;
      }
      if ($file){
         fclose($file);
      }
      /* Выполнение запроса */
      $time_start=microtime(true);
      @ $SQLResult=mysql_query($query);
      $time_end=microtime(true);
      $this->DataDate=time();
      $time_exec=$time_end-$time_start;
      /* Обработка ошибки запроса */
      if (!$SQLResult){
         if ($this->Debug){
            die('Error from query "'.$query.'": '.mysql_error());
         }else{
            $this->errno=mysql_errno();
            $this->error=mysql_error();
            return false;
         }
      }
      /* Проверка пиковой нагрузки */
      $peak_filename=$this->CachePath.'/'.$this->PeakFilename;
      if (@$file=fopen($peak_filename, 'r')){
         flock($file, LOCK_SH);
         $fdata=file($peak_filename);
         foreach ($fdata as $key=>$value){
            $this->Peak[$key]=trim($value);
         }
         $this->Peak[0]=floatval($this->Peak[0]);
      }
      if ($file){
         fclose($file);
      }
      if ($time_exec>$this->Peak[0]){
         $this->Peak=array(
            $time_exec,
            date('r'),
            $query,
            $_SERVER['SCRIPT_FILENAME'],
         );
         $file=fopen($peak_filename, 'w');
         flock($file, LOCK_EX);
         fwrite($file, implode("\n", $this->Peak));
         fclose($file);
      }
      /* Получение названия полей */
      $nf=mysql_num_fields($SQLResult);
      for ($i=0; $i<$nf; $i++){
         $this->ResultData['fields'][$i]=mysql_fetch_field($SQLResult, $i);
      }
      /* Получение данных */
      $nr=mysql_num_rows($SQLResult);
      for ($i=0; $i<$nr; $i++){
         $this->ResultData['data'][$i]=mysql_fetch_row($SQLResult);
      }
      /* Запись кэша */
      $file=fopen($filename, 'w');
      flock($file, LOCK_EX);
      fwrite($file, serialize($this->ResultData));
      fclose($file);
      return true;
   }

   /*** Количество полей в запросе ***/
   function num_fields(){
      return sizeof($this->ResultData['fields']);
   }

   /*** Название указанной колонки результата запроса ***/
   function field_name($num){
      if (isset($this->ResultData['fields'][$num])){
         return $this->ResultData['fields'][$num]->name;
      }else{
         return false;
      }
   }

   /*** Информация о колонке из результата запроса в виде объекта ***/
   function fetch_field($num){
      if (isset($this->ResultData['fields'][$num])){
         return $this->ResultData['fields'][$num];
      }else{
         return false;
      }
   }

   /*** Длина указанного поля ***/
   function field_len($num){
      if (isset($this->ResultData['fields'][$num])){
         return $this->ResultData['fields'][$num]->max_length;
      }else{
         return false;
      }
   }

   /*** Тип указанного поля результата запроса ***/
   function field_type($num){
      if (isset($this->ResultData['fields'][$num])){
         return $this->ResultData['fields'][$num]->type;
      }else{
         return false;
      }
   }

   /*** Флаги указанного поля результата запроса ***/
   function field_flags($num){
      if (!isset($this->ResultData['fields'][$num])){
         return false;
      }
      $result=array();
      if ($this->ResultData['fields'][$num]->not_null){
         $result[]='not_null';
      }
      if ($this->ResultData['fields'][$num]->primary_key){
         $result[]='primary_key';
      }
      if ($this->ResultData['fields'][$num]->unique_key){
         $result[]='unique_key';
      }
      if ($this->ResultData['fields'][$num]->multiple_key){
         $result[]='multiple_key';
      }
      if ($this->ResultData['fields'][$num]->blob){
         $result[]='blob';
      }
      if ($this->ResultData['fields'][$num]->unsigned){
         $result[]='unsigned';
      }
      if ($this->ResultData['fields'][$num]->zerofill){
         $result[]='zerofill';
      }
      if ($this->ResultData['fields'][$num]->binary){
         $result[]='binary';
      }
      if ($this->ResultData['fields'][$num]->enum){
         $result[]='enum';
      }
      if ($this->ResultData['fields'][$num]->auto_increment){
         $result[]='auto_increment';
      }
      if ($this->ResultData['fields'][$num]->timestamp){
         $result[]='timestamp';
      }
      return implode(' ', $result);
   }

   /* Количество рядов результата запроса */
   function num_rows(){
      return sizeof($this->ResultData['data']);
   }

   /* Обрабатывает ряд результата запроса и возвращает неассоциативный массив */
   function fetch_row(){
      if (($this->NextRowNo+1)>$this->num_rows()){
         return false;
      }
      $this->NextRowNo++;
      return $this->ResultData['data'][$this->NextRowNo-1];
   }

   /* Обрабатывает ряд результата запроса и возвращает ассоциативный массив */
   function fetch_assoc(){
      if (($this->NextRowNo+1)>$this->num_rows()){
         return false;
      }
      for ($i=0; $i<$this->num_fields(); $i++){
         $result[$this->ResultData['fields'][$i]->name]=
            $this->ResultData['data'][$this->NextRowNo][$i];
      }
      $this->NextRowNo++;
      return $result;
   }
   function fetch_assoc_num(){
      if (($this->NextRowNo+1)>$this->num_rows()){
         return false;
      }
      for ($i=0; $i<$this->num_fields(); $i++){
         $result[$this->ResultData['fields'][$i]]=
            $this->ResultData['data'][$this->NextRowNo][$i];
      }
      $this->NextRowNo++;
      return $result;
   }   
}
?>