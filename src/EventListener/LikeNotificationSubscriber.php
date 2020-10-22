<?php


namespace App\EventListener;

use App\Entity\LikeNotification;
use App\Entity\MicroPost;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Notifier\Notification\Notification;

/**
 * @description Responsible to tell doctrin, what events it wants to subscribe
 */
class LikeNotificationSubscriber implements EventSubscriber
{

    // EVENTS that the subscriber is subscribed to
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush
        ];
    }

    //METHOD NAME has to match the event names >> Events::onFlush <<
    public function onFlush(OnFlushEventArgs $args){
        $em  = $args->getEntityManager();

        //FETCH FROM ENITYMANAGER
        // - KEEPS TRACK OF ALL THE CHANGES OF ALL DIFFERENT ENTITIES.
        // - INLUDES PERSISTING, NEW ENTITY MODIFING ITS PROPERTIES
        // - ADDING/REMOVING ELEMENTS to different collections etc

        // FOR EXAMPLE A CERTAIN COLLECTION DID CHANGE
        $uow = $em->getUnitOfWork();

        //LIST OF ALL PERSISTENT COLLECTION OBJECTS or Objects which implement the doctrine collection interface
        /**
         * @var PersistentCollection $collectionUpdate
         */
        foreach($uow->getScheduledCollectionUpdates() as $collectionUpdate){
            if( !$collectionUpdate->getOwner() instanceof MicroPost){
                continue;
            }
            #dump($collectionUpdate->getMapping());die;
            if( 'likedBy' !== $collectionUpdate->getMapping()['fieldName']){
                continue;
            }

            // ELEMENT WURDE DER COLLECION HINZGEFÜGT?!
            $insertDiff = $collectionUpdate->getInsertDiff();

            // ELEMENT WURDE DER COLLECION HINZGEFÜGT?!
            if(!count($insertDiff)){
                return;
            }

            /** @var MicroPost $microPost */
            $microPost = $collectionUpdate->getOwner();

            $notification = new LikeNotification();
            $notification->setUser($microPost->getUser());
            $notification->setMicroPost($microPost);
            $notification->setLikedBy(reset($insertDiff));

            $em->persist($notification);

            $uow->computeChangeSet($em->getClassMetadata(LikeNotification::class), $notification);


        }

    }

}