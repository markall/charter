ALTER TABLE mm_func
ADD crewboardinglocationtext varchar(250) AFTER crewboardinglocationid,
ADD cateringdeliverylocationtext varchar(250) AFTER cateringdeliverylocationid,
ADD guestboardinglocationtext varchar(250) AFTER guestboardinglocationid,
ADD gueststopover1locationtext varchar(250) AFTER gueststopover1locationid,
ADD gueststopover2locationtext varchar(25) AFTER gueststopover2locationid,
ADD guestdisembarklocationtext varchar(255) AFTER guestdisembarklocationid,
ADD crewdisembarklocationtext varchar(255) AFTER crewdisembarklocationid