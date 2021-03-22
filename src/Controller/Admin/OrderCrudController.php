<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Admin\OrderCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    private $entityManager;
    private $crudUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, CrudUrlGenerator $crudUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
       $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', "fas fa-box-open")->linkToCrudAction('updatePreparation');
       $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', "fas fa-truck")->linkToCrudAction('updateDelivery');
        
       return $actions
            ->add('detail', $updatePreparation)
            ->add('detail', $updateDelivery)
            ->add('index', 'detail'); 
    }

    public function updatePreparation(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        if($order->getState() == 2) {
            $this->addFlash('notice', "<span style='color:red;'><strong>La commande " . $order->getReference() . " est déjà <u>en cours de préparation</u></strong></span> ");
            
        } else {
            
            $order->setState(2);

            $this->entityManager->flush();

            $this->addFlash('notice', "<span style='color:green;'><strong>La commande " . $order->getReference() . " est bien <u>en cours de préparation</u></strong></span> ");

            $mail = new Mail();
            $content = "Bonjour ". $order->getUser()->getFirstname(). "<br>" . "Votre commande est en cours de préparation !";
            $mail->send(
                $order->getUser()->getEmail(), 
                $order->getUser()->getFirstname(), 
                "Votre commande numéro : " . $order->getReference() . " eCommerce - Symfony", 
                $content
            );
            
        }

        $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        if($order->getState() == 3) {
            $this->addFlash('notice', "<span style='color:red;'><strong>La commande " . $order->getReference() . " est déjà <u>en cours de livraison</u></strong></span> ");
            
        } else {
            
            $order->setState(3);

            $this->entityManager->flush();

            $this->addFlash('notice', "<span style='color:green;'><strong>La commande " . $order->getReference() . " est bien <u>en cours de livraison</u></strong></span> ");

            $mail = new Mail();
            $content = "Bonjour ". $order->getUser()->getFirstname(). "<br>" . "Votre commande est en cours de livraison !";
            $mail->send(
                $order->getUser()->getEmail(), 
                $order->getUser()->getFirstname(), 
                "Votre commande numéro : " . $order->getReference() . " eCommerce - Symfony", 
                $content
            );
        }

        $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud) : Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Passée le'),
            TextField::new('user.getFullName', 'Client')->hideOnForm(),
            TextEditorField::new('delivery', 'Adresse de livraison'),
            MoneyField::new('total', 'Total produit')->setCurrency('EUR')->hideOnForm(),
            ChoiceField::new('carrierName', 'Transporteur')->setChoices([
                'Colissimo' => 'Colissimo',
                'Chronopost' => 'Chronopost'
            ]),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
            ChoiceField::new('state', 'Etat de la commande')->setChoices([
                'Non payée' => 0,
                'Payée' => 1,
                'Préparation en cours' => 2,
                'Livraison en cours' => 3
            ]),
            // ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex(),
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnForm()
        ];
    }
}
