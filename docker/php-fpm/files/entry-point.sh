#!/bin/sh

if [ ! -f /.configured ]; then
  for file in /entry-point.d/*.sh; do
    if [ -f ${file} ]; then
      ${file}

      if [ $? -ne 0 ]; then
        echo "failed configuring container ..."

        exit 1
      fi
    fi
  done

  touch /.configured
fi

exec "$@"
