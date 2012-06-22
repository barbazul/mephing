<?php

class ContentsTask extends Task
{
	private $package;
	private $node;
	private $file;

	public function setFile($file)
	{
		$this->file = $file;
	}

    public function init()
    {
    }

    public function main()
    {
    	$this->package = simplexml_load_file($this->file);
    	$this->node = $this->getContentsNode();
    	
    	// Fist start wih code
        $codePath = $this->project->getProperty('path.code');
        
		if (is_dir($codePath)) {
			$this->node = $this->node->addChild('target');
			$this->node->addAttribute('name', 'mage' . $this->project->getProperty('module.codepool'));
			$this->node = $this->node->addChild('dir');
			$this->node->addAttribute('name', $this->project->getProperty('module.namespace'));
			$this->node = $this->node->addChild('dir');
			$this->node->addAttribute('name', $this->project->getProperty('module.name'));

            /*
             * I keep the following in different calls because the 
             * build.properties file may rule something different than the 
             * default. The whole bunch could be easily replaced with 
             * walkDir('path.code')
             */
             
            // Add Block files
            $path = $this->project->getProperty('path.block');
            
            if (is_dir($path)) {
                $parent = $this->node;
                $this->node = $this->node->addChild('dir');
                $this->node->addAttribute('name', 'Block');
                $this->walkDir($path);
                $this->node = $parent;
            }
            
            // Add Helper files
            $path = $this->project->getProperty('path.helper');
            
            if (is_dir($path)) {
                $parent = $this->node;
                $this->node = $this->node->addChild('dir');
                $this->node->addAttribute('name', 'Helper');
                $this->walkDir($path);
                $this->node = $parent;
            }

            // Add Model files
            $path = $this->project->getProperty('path.model');
            
            if (is_dir($path)) {
                $parent = $this->node;
                $this->node = $this->node->addChild('dir');
                $this->node->addAttribute('name', 'Model');
                $this->walkDir($path);
                $this->node = $parent;
            }
            
            // Add controller files
            $path = $this->project->getProperty('path.controllers');
            
            if (is_dir($path)) {
                $parent = $this->node;
                $this->node = $this->node->addChild('dir');
                $this->node->addAttribute('name', 'controllers');
                $this->walkDir($path);
                $this->node = $parent;
            }
            
            // Add configuration files
            $path = $this->project->getProperty('path.etc');
            
            if (is_dir($path)) {
                $parent = $this->node;
                $this->node = $this->node->addChild('dir');
                $this->node->addAttribute('name', 'etc');
                $this->walkDir($path);
                $this->node = $parent;
            }
            
            // Add setup files
            $path = $this->project->getProperty('path.sql');
            
            if (is_dir($path)) {
                $parent = $this->node;
                $this->node = $this->node->addChild('dir');
                $this->node->addAttribute('name', 'sql');
                $this->walkDir($path);
                $this->node = $parent;
            }
            
            $this->node = $this->getContentsNode();
            
		}
		
		// Second go with design
        $frontPath = $this->project->getProperty('path.design.front');
        $adminPath = $this->project->getProperty('path.design.admin');
        
        if (is_dir($frontPath) || is_dir($adminPath)) {

            $this->node = $this->node->addChild('target');
            $this->node->addAttribute('name', 'magedesign');

            $design = $this->node;

            if (is_dir($frontPath)) {
                $layoutPath = $this->project->getProperty('path.design.front.layout');
                $templatePath = $this->project->getProperty('path.design.front.template');
                
                if (file_exists($layoutPath) || is_dir($templatePath)) {
                    $this->node = $this->node->addChild('dir');
                    $this->node->addAttribute('name', 'frontend');
                    $this->node = $this->node->addChild('dir');
                    $this->node->addAttribute('name', $this->project->getProperty('design.front.package'));
                    $this->node = $this->node->addChild('dir');
                    $this->node->addAttribute('name', $this->project->getProperty('design.front.theme'));

                    $theme = $this->node;
                    
                    if (file_exists($layoutPath)) {
                        $this->node = $this->node->addChild('dir');
                        $this->node->addAttribute('name', 'layout');
                        $this->node = $this->node->addChild('file');
                        $this->node->addAttribute('name', $this->project->getProperty('design.front.layout'));
                        $this->node->addAttribute('hash', md5_file($layoutPath));
                        $this->node = $theme;
                    }
                    
                    if (is_dir($templatePath)) {
                        $this->node = $this->node->addChild('dir');
                        $this->node->addAttribute('name', 'template');
                        $this->node = $this->node->addChild('dir');
                        $this->node->addAttribute('name', $this->project->getProperty('design.namespace'));
                        $this->walkDir($templatePath);
                    }
                }
                
                $this->node = $design;
                
            }
            
            if (is_dir($adminPath)) {
                $layoutPath = $this->project->getProperty('path.design.admin.layout');
                $templatePath = $this->project->getProperty('path.design.admin.template');
                
                if (file_exists($layoutPath) || is_dir($templatePath)) {
                    $this->node = $this->node->addChild('dir');
                    $this->node->addAttribute('name', 'adminhtml');
                    $this->node = $this->node->addChild('dir');
                    $this->node->addAttribute('name', $this->project->getProperty('design.admin.package'));
                    $this->node = $this->node->addChild('dir');
                    $this->node->addAttribute('name', $this->project->getProperty('design.admin.theme'));
                    
                    $theme = $this->node;
                    
                    if (file_exists($layoutPath)) {
                        $this->node = $this->node->addChild('dir');
                        $this->node->addAttribute('name', 'layout');
                        $this->node = $this->node->addChild('file');
                        $this->node->addAttribute('name', $this->project->getProperty('design.admin.layout'));
                        $this->node->addAttribute('hash', md5_file($layoutPath));
                        $this->node = $theme;
                    }
                    
                    if (is_dir($templatePath)) {
                        $this->node = $this->node->addChild('dir');
                        $this->node->addAttribute('name', 'template');
                        $this->node = $this->node->addChild('dir');
                        $this->node->addAttribute('name', $this->project->getProperty('design.namespace'));
                        $this->walkDir($templatePath);
                    }
                }
                
                $this->node = $design;
            }
            
            $this->node = $this->getContentsNode();;
        }
        
        // Third place is for locale files
        $localePath = $this->project->getProperty('path.locale');
        
        if (is_dir($localePath)) {
            
            $this->node = $this->node->addChild('target');
            $this->node->addAttribute('name', 'magelocale');
            
            $d = dir($localePath);
            
            while(false !== ($entry = $d->read())) {
                if ($entry == '.' || $entry == '..') continue;
                if (is_dir($localePath . $entry) && file_exists($localePath . $entry . '/' . $this->project->getProperty('module.translation'))) {
                    $locale = $this->node->addChild('dir');
                    $locale->addAttribute('name', $entry);
                    $translation = $locale->addChild('file');
                    $translation->addAttribute('name', $this->project->getProperty('module.translation'));
                    $translation->addAttribute('hash', md5_file($localePath . $entry . '/' . $this->project->getProperty('module.translation')));
                }
            }
            
            $this->node = $this->getContentsNode();;
        }
        
        // Finally, the module activation file
        $this->node = $this->node->addChild('target');
        $this->node->addAttribute('name', 'mage');
        $this->node = $this->node->addChild('dir');
        $this->node->addAttribute('name', 'app');
        $this->node = $this->node->addChild('dir');
        $this->node->addAttribute('name', 'etc');
        $this->node = $this->node->addChild('dir');
        $this->node->addAttribute('name', 'modules');
        $this->node = $this->node->addChild('file');
        $filename = $this->project->getProperty('module.namespace') . '_' . $this->project->getProperty('module.name') . '.xml';
        $this->node->addAttribute('name', $filename);
        $this->node->addAttribute('hash', md5_file($this->project->getProperty('path.modules') . $filename));
        
        // TODO Skin, js, lib, unit tests
		
		$this->package->asXML($this->file);
    }
    
    private function walkDir($path)
    {
        $path = rtrim($path, '/') . '/';
		$d = dir($path);
		
		while (false !== ($entry = $d->read())) {
			if ($entry == '.' || $entry == '..') continue;
			
			if (is_dir($path . $entry)) {
			    $parent = $this->node;
			    $this->node = $this->node->addChild('dir');
			    $this->node->addAttribute('name', $entry);
			    $this->walkDir($path . $entry);
			    $this->node = $parent;
			} else {
			    $file = $this->node->addChild('file');
			    $file->addAttribute('name', $entry);
			    $file->addAttribute('hash', md5_file($path . $entry));
			}
			
		}
    }
    
    private function getContentsNode()
    {
    	$contents = $this->package->xpath('//contents');
    	return reset($contents);
    }
}

/*
<contents>
    <target name="mage">
        <dir name="app">
            <dir name="etc">
                <dir name="modules">
                    <file name="SemExpert_Nps.xml" hash="77f667c40be9c2fa167279e2bbe243b7"/>
                </dir>
            </dir>
        </dir>
    </target>
</contents>




*/
