FROM alpine:3.8

# Install needed software
RUN apk update
RUN apk add docker
RUN apk add sshpass
RUN apk add openssh-client

# Run docker daemon
RUN apk add openrc
RUN rc-update add docker boot