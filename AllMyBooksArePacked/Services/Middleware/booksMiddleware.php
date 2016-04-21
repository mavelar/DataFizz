<?
  class booksMiddleware

  {

  	public function call() {
  		$this->next->call();
  	}

  }
?>