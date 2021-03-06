<?php

namespace PicoPageFolders\Managers;

class LanguageManager {
    private $currentLang;
    private $availableLanguages = array();
    
    public function setLanguage($language) {
        $this->currentLang = $language;
    }

    public function getLanguage() {
        return $this->currentLang;
    }

    public function addAvailableLanguage($lanugage) {
        if (in_array($lanugage, $this->availableLanguages)) return;
        array_push($this->availableLanguages, $lanugage);
    }

    public function getLanguageFromFullId($id) {
        return substr($id, -2);
    }

    public function getAlternativeLanguagePages($page) {
        $otherLanguages = array();        
        foreach ($this->availableLanguages as $availableLanguage) {
            if ($this->currentLang == $availableLanguage) continue;
            $otherPage = $page;
            $otherPage['url'] = str_replace(
                "lang=$this->currentLang",
                "lang=$availableLanguage",
                $otherPage['url']);
            $otherLanguages[$availableLanguage] = $otherPage['url'];
        }
        return $otherLanguages;
    }

    public function getLanguageAwarePage($page) {
        $id = $page['id'];
        $langExtension = substr($id, -3);
        $newId = substr($id, 0, -strlen($langExtension));
        $page['id'] = $newId;
        $page['url'] = $this->replaceLastOccurence($langExtension, '', $page['url']);
        $page['url'] = $this->replaceLastOccurence(rawurlencode($langExtension), '', $page['url']);
        $page['url'] .= strpos($page['url'], '?') !== false ? '&' : '?';
		$page['url'] .= 'lang='.$this->currentLang;
        return $page;
    }

    public function getExtendedUrlFromUrl($url) {
        if (!isset($url))  return "index/$this->currentLang";
        if (substr($url, -1) != '/') $url .= '/';
        $url .= $this->currentLang;
        return $url;
    }

    private function replaceLastOccurence($search, $replacement, $subject) {
        $regex = '/'.preg_quote(strrev($search), '/').'/';
        $replacement = strrev($replacement);
        $subject = strrev($subject);

        $replaced = preg_replace($regex, $replacement, $subject, 1);
        return strrev($replaced);
    }
}