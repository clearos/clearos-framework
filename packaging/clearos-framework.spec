#------------------------------------------------------------------------------
# P A C K A G E  I N F O
#------------------------------------------------------------------------------

Name: clearos-framework
Group: Development/Languages
Version: 5.9.9.3
Release: 2.1%{dist}
Summary: ClearOS framework
License: CodeIgniter and LGPLv3
Packager: ClearFoundation
Vendor: ClearFoundation
Source: %{name}-%{version}.tar.gz
Requires: clearos-base
Requires: webconfig-php
Requires(post): /sbin/service
Buildarch: noarch
Buildroot: %_tmppath/%name-%version-buildroot

%description
ClearOS framework

#------------------------------------------------------------------------------
# B U I L D
#------------------------------------------------------------------------------

%prep
%setup -q
%build

#------------------------------------------------------------------------------
# I N S T A L L  F I L E S
#------------------------------------------------------------------------------

%install
rm -rf $RPM_BUILD_ROOT

mkdir -p -m 755 $RPM_BUILD_ROOT/usr/clearos/apps
mkdir -p -m 755 $RPM_BUILD_ROOT/usr/clearos/themes
mkdir -p -m 755 $RPM_BUILD_ROOT/usr/clearos/framework/htdocs
mkdir -p -m 755 $RPM_BUILD_ROOT/usr/clearos/webconfig/etc/httpd/conf.d
mkdir -p -m 755 $RPM_BUILD_ROOT/var/clearos/framework
mkdir -p -m 1777 $RPM_BUILD_ROOT/var/clearos/framework/cache
mkdir -p -m 1777 $RPM_BUILD_ROOT/var/clearos/framework/tmp

cp -r application $RPM_BUILD_ROOT/usr/clearos/framework
cp -r htdocs $RPM_BUILD_ROOT/usr/clearos/framework
cp -r shared $RPM_BUILD_ROOT/usr/clearos/framework
cp -r system $RPM_BUILD_ROOT/usr/clearos/framework

install -m 0644 license.txt $RPM_BUILD_ROOT/usr/clearos/framework
install -m 0644 packaging/framework.conf $RPM_BUILD_ROOT/usr/clearos/webconfig/etc/httpd/conf.d

#------------------------------------------------------------------------------
# I N S T A L L
#------------------------------------------------------------------------------

%post

if [ $1 -eq 1 ]; then
	/sbin/service webconfig condrestart >/dev/null 2>&1
fi

exit 0

#------------------------------------------------------------------------------
# C L E A N  U P
#------------------------------------------------------------------------------

%clean
rm -rf $RPM_BUILD_ROOT

#------------------------------------------------------------------------------
# F I L E S
#------------------------------------------------------------------------------

%files
%defattr(-,root,root)
%dir /usr/clearos/apps
%dir /usr/clearos/framework
%dir /usr/clearos/themes
%dir /var/clearos/framework
%dir %attr(1777,root,root) /var/clearos/framework/cache
%dir %attr(1777,root,root) /var/clearos/framework/tmp
/usr/clearos/framework
/usr/clearos/webconfig/etc/httpd/conf.d/framework.conf
