cd ../prod
git checkout dev
git pull
git checkout master
git merge dev
git push
cd ../dev