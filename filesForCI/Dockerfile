FROM alpine:3.8

# Install needed software
RUN apk update
RUN apk add docker
RUN apk add sshpass
RUN apk add openssh-client
RUN apk add git
RUN apk add wget
RUN apk add php7 php7-simplexml php7-tokenizer php7-xmlwriter php7-mbstring php7-xml php7-pgsql php7-curl php7-dom php7-session
RUN apk add composer

# Run docker daemon
RUN apk add openrc
RUN rc-update add docker boot
