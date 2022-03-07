<?php
namespace App\RegisterTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterTest extends WebTestCase {
  
    
    public function testRegister(){
        $client = static::createClient();

        $crawler = $client->request('POST','/register');
        
        $formCrawler = $crawler->selectButton('Enregistrez vous');
        $form = $formCrawler->form();

        $form['registration_form[firstname]'] = 'John';
        $form['registration_form[lastname]'] = 'Doe';
        $form['registration_form[email]'] = 'John@Doe.com';
        $form['registration_form[plainPassword]'] = 'azerty';
        $form['registration_form[plainPassword2]'] = 'azerty';
        $form['registration_form[adresse]'] = 'adresse';
        $form['registration_form[tel]'] = 'tel';
        $client->submit($form);
        //
        //echo $client->getResponse()->getContent();
        
        
    }
}