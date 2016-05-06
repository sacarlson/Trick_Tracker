for f in $(find -type l);do cp --remove-destination $(readlink $f) $f;done;
