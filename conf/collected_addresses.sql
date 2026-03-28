INSERT INTO collected_addresses (
  changed,
  email,
  name,
  user_id,
  type
) SELECT  
cc.changed,
   cc.email,
   cc.name,
   cc.user_id,
   1
FROM collected_contacts cc 
LEFT JOIN collected_addresses ca 
ON (cc.email = ca.email) AND (cc.user_id = ca.user_id) 
WHERE ca.email IS  NULL;