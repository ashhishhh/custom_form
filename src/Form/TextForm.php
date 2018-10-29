<?php
namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements a custom form.
 */
class TextForm extends FormBase {
    /**
     * {@inheritDoc}
     */
    public function getFormId() {
        return 'custom_text_form';
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $result = $form_state->get('result');
        
        $form['sentence'] = [
          '#type' => 'textarea',
          '#cols' => 10,
          '#title' => t('Enter your sentence:'),
          '#required' => TRUE,
          '#resizable' => FALSE,
          '#prefix' => '<div class="snt-wrapper">',
          '#suffix' => '</div>',
        ];
        

        if(isset($result)) {
            //kint($result);
            $form['result'] = array
              (
              '#markup' => '<div class="rslt-wrapper"><h3>Result:</h3><p>' . $result . '</p></div>',
            );
        }

        $form['conversion'] = [
          '#type' => 'select',
          '#title' => $this
            ->t('Change it for:'),
          '#options' => [
            '1' => $this
              ->t('Random'),
            '2' => $this
              ->t('Reverse processing'),
            '3' => $this
              ->t('Capitalize every second word'),
          ],
        ];
        $form['#attached']['library'][] = 'custom_form/custom_lib';
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = [
          '#type' => 'submit',
          '#value' => $this->t('Submit'),
          '#button_type' => 'primary',
        ];
        return $form;
    }

    /**
     * {@inheritDoc}
     */
//    public function validateForm(array &$form, FormStateInterface $form_state) {
//        if (strlen($form_state->getValue('sentence')) < 3) {
//            $form_state->setErrorByName('sentence', $this->t('The sentence is too short. Please enter a full sentence.'));
//        }
//    }
    
    /**
     * {@inheritDoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $sentence = $form_state->getValue('sentence');
        $conversion = $form_state->getValue('conversion');
        $sentence = trim($sentence);
        $pieces = explode(" ", $sentence);
        
        switch ($conversion) {
            case 1:
                shuffle($pieces);
                break;
            case 2:
                $pieces = array_reverse($pieces, false);
                break;
            case 3:
                foreach ($pieces as $key => $piece) {
                    if ($key % 2 == 1) {
                        $pieces[$key] = ucfirst($piece);
                    }
                }

            default:
                break;
        }
        $result = implode(" ", $pieces);
        $form_state->set('result', $result);
        
        //$form_state->setStorage(['result'], $result);
        $form_state->setRebuild(TRUE);
        
    }
}
