<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormProcessor
{
  public function processForm(FormInterface $form, Request $request)
  {
      $data = json_decode($request->getContent(), true);

      $form->submit($data);
  }
}
