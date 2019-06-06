<?php
 
namespace App\DataHandler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FormHandler
{
	private $formFactory;

	public function __construct(FormFactoryInterface $formFactory)
	{
		$this->formFactory = $formFactory;
	}

	public function handleForm(Request $request, string $formType, $data = null, array $options = [])
	{
		$form = $this->formFactory->create($formType, $data, $options);
		$form->submit($request->request->all());

		if (!($form->isSubmitted() && $form->isValid())) {
			$error = $form->getErrors(true)->current()->getMessage();
			throw new BadRequestHttpException($error);
		}

		return $form->getData();
	}	
}
