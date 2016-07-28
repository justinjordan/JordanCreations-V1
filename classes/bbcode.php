<?php

class BBCode
{
	public function filter($text)  // return VOID
	{
		
		$text = $this->replaceBreaks($text);
		$text = $this->replaceImages($text);
		$text = $this->replaceEmail($text);
		$text = $this->removeSlashes($text);
		$text = $this->replaceHyphens($text);
		
		return $text;
	}
	
	public function removeSlashes($text)
	{
		return stripslashes($text);
	}
	
	public function replaceBreaks($text)
	{
		$code = array("[br]", "[BR]");
		
		$text = str_replace($code, "</p><p>", $text);
		$text = preg_replace('!\n+!', '</p><p>', $text);
		
		return $text;
	}
	
	public function replaceImages($text)
	{
		$codeOpen = array("[img]", "[IMG]");
		$text = str_replace($codeOpen, "<img src=\"", $text, $countOpen);
		$codeClose = array("[/img]", "[/IMG]");
		$text = $text = str_replace($codeClose, "\"/>", $text, $countClose);
		
		if ($countOpen != $countClose)
			$text = $this->removeAll($text);
			
		return $text;
	}
	
	public function replaceEmail($text)
	{
		$regex = '/(\S+@\S+\.\S+)/';
		$replace = '<a href="mailto:$1" target="_blank">$1</a>';

		return preg_replace($regex, $replace, $text);
	}
	
	private function removeAll($text)  // in case of errors remove all html that was inserted
	{
		$html = array("</p><p>", "<img src=\"", "\"/>");
		
		return str_replace($html, "", $text);
	}
	
	private function replaceHyphens($text)
	{
		$html = array("--");
		
		return str_replace($html, "&mdash;", $text);
	}
	
}

?>
