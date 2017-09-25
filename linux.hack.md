# Поиск с помощью grep 

> grep -rnw './' --exclude-dir={uploads,cache} --color -e 'youtube'


# Архивы

### Создем 

#### tar

> tar -cf txt.tar *.txt
>#для gz
> tar -cvzf files.tar.gz ~/files

#### zip
> zip -r имя_архива folder

### Распаковываем
> tar -xvf /path/to/archive.tar.bz2
> unzip имя_файла.zip -d /tmp


