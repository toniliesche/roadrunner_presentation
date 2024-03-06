ARG ubuntu_version
FROM phpughh/roadrunner/ubuntu:${ubuntu_version}

ARG graylog_sidecar_version
ARG graylog_sidecar_version_patch
RUN apt update && \
    apt install --no-install-recommends -y curl ca-certificates && \
    curl -L -o /tmp/graylog-sidecar.deb https://github.com/Graylog2/collector-sidecar/releases/download/${graylog_sidecar_version}/graylog-sidecar_${graylog_sidecar_version}-${graylog_sidecar_version_patch}_amd64.deb && \
    dpkg -i /tmp/graylog-sidecar.deb && \
    rm -rf /tmp/graylog-sidecar.deb && \
    apt purge -y curl ca-certificates && \
    apt autoremove -y && \
    apt clean && \
    apt autoclean -y

CMD [ "/usr/bin/graylog-sidecar", "-c", "/etc/graylog-sidecar.yml" ]
