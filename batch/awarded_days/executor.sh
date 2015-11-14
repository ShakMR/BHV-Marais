python set_awarded.py > inserts.sql && \
echo "DELETE FROM Awards WHERE idAward>-1; ALTER TABLE Awards AUTO_INCREMENT = 0" | mysql -u bhv --password=dwph4DVq bhv_dev
mysql -u bhv --password=dwph4DVq bhv_dev <inserts.sql