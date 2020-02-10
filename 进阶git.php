<?php
/**
 *
 *
 *
 *
 *
 * 2. git 账户设置
 *    设置邮箱和用户名的三种方式
 *      git config --global user.name "name"(基本不用，给整个计算机一次性设置)
 *
 *      git config --system user.name "name"(给当前用户进行一次性设置)
 *          会在 /c/Users/USERNAME/.gitconfig 文件中显示
 *      git config --local user.name "name"(给当前项目一次性设置)
 *          会在当前项目的 .git/config 里面生成用户名和密码
 *
 *    如果同时设置 那么优先级  3》2》1
 *
 *    删除用户的账户信息配置
 *      git config --local --unset user.name
 *
 *
 *    操作  ：
 *    工作区修改 ->>  工作区未修改状态
 *    1. 如果修改一个已经提交到了对象区的文件 那么该文件会退回到工作区  如果放弃这个修改
 *      使用  git --checkout filename
 *
 *    暂存区 -》》 工作区
 *    2. 如果一个文件已经修改并且 add 到了暂存区  那么放弃这个修改 使用
 *            git reset HEAD  filename
 *
 *    日志操作 :
 *      git log  显示提交的状态
 *      显示用户名问题 : 只对修改后提交的用户名有效，修改之前的提交仍然显示的是之前配置的用户名
 *      和密码
 */


/**
 *  git操作和ignore
 *
 *  git删除文件
 *      git rm [filename]
 *
 *  删除已经提交的文件，内容会被放到暂存区
 *  1.如果要彻底删除， 在暂存区把删除操作再次提交一次
 *      git commit -m "彻底删除"
 *
 *  2.如果要撤销这个在暂存区的删除
 *      git reset HEAD filename  将暂存区的文件退回到工作区
 *      git rm --cached filename  将暂存区的文件进行删除但是保留工作区
 *      git rm filename          将暂存区的文件和工作区的文件都进行删除
 *      git checkout -- filename 将工作区的删除状态进行撤销操作
 *
 *
 *  如果是操作系统的rm命令 那么会直接将删除放入工作区  直接进入到第二部的步骤
 *
 *
 *  git 重命名文件
 *     重命名相当于把文件删除并且重新创建了一个
 *     git mv filename
 *
 *      修改了名字的文件会像删除一样回到暂存区
 *
 *      系统的重命名会全部回到工作区 直接进行add 删除老文件  保留新文件即可完成重命名
 *
 *  注释从写 :
 *      git commit --amend  -m "msg"
 *
 *  忽略文件 ：
 *      vi .gitignore
 *      通配符:
 *          *.filename
 *          ！filename
 *          注释 : #
 *          忽略目录: dir/
 *      如果是空目录 那么默认自动忽略
 */


/**
 *  4. 分支
 *      查看分支  git branch
 *      创建分支  git branch [branchname]
 *      切换分支  git checkout [branchname]
 *      删除分支  git branch -d [branchname]
 *          不能删除的情况 : 1. 不能删除当前分支
 *                          2. 不能删除一个未合并的分支
 *                git branch -D   强行删除一个分支
 *      创建并切换分支   git checkout -b [branchname]
 *      合并一个分支  git merge [branchname]
 *
 *      切换分支注意事项:
 *          如果在一个分支中进行了写操作，并且没有add的情况下， 可以切换分支
 *          并且在新分支中会显示该文件
 *          如果在一个分支中进行写操作并且提交了，那么无法切换该分支
 *
 *      查看分支最后一次提交的sha1值
 *          git branch -v
 */


/**
 *  分支的合并与冲突
 *     如果一个分支(dev)靠前 ，另一个落后 (master), 如果分支不冲突 ，那么master可以
 *    通过merge 直接追赶上dev。 称为 fast forward。
 *      fast forward 的本质就是快速的移动指针， 注意跳过中间的commit， 跳过的记录仍然会保存
 *      jin分支的具体信息不会进行保留
 *
 *  禁止 fast forward
 *      git merge --no-ff
 *      1. 两个分支不会归于一点 (主动进行合并的分支会往前一步)
 *      2. 分支的具体信息会进行保留
 *
 *  解决冲突 :
 *      1. 解决冲突文件
 *      2. git add   然后  git commit
 *
 *      注意  : master 在merge 时， 如果遇到冲突 并解决，则解决冲突会进行两次提交：
 *       一次是最终提交 ， 一次是将对方dev的提交信息也拿来了
 *
 *      如果一方落后， 另一方前进，则落后方可以直接通过merge合并到前进方。
 *      如果前进的一方， 合并落后的一方  则什么也不会发生
 *      如果同时修改的双方进行合并 则会产生冲突
 */

/**
 *  6. 版本穿梭
 *      日志: (GIT LOG 只能看见当前分支的日志记录)
 *          git log  普通日志
 *          git log --graph   图形化日志
 *          git log --graph --pretty=oneline --abbrev-commit   只显示sha1值的前几位和注释
 *
 *      回退到上一次的commit
 *          git reset --hard HEAD^
 *      回退到上两次的操作
 *          git reset --hard HEAD~2
 *
 *      详细日志 ： reflog
 *          问题 ： 如果找不到某一次的操作 那么通过git  reflog  找到sha1 值
 *              然后再次进行提交
 */


/**
 *      checkout 放弃与游离操作
 *        checkout 放弃修改， 放弃的是工作区中的修改 相对于暂存区和对象区中的修改
 *
 *       reset HEAD : 将之前提交的内容回退到工作区中
 *
 *      游离穿梭 :
 *          git checkout [sha1]    游离穿梭到指定的版本中
 *          特点 :
 *          如果穿梭后 进行了 修改  则必须提交
 *          此时是创建一个新分支的好时机
 *
 *          问题 : 如果修改了过去分支的代码 ， 那么之后的代码都没有进行修改则毫无意义
 *          此时创建一个新的分支才能生效
 *
 */


/**
 *  stash 和 diff 操作
 *      分支重命名 :  git branch -m  oldname  newname
 *
 *  stash : 保存现场
 *     规范1 : 在功能没有开发完之前不要commit
 *     规定1 ：在分支没有commit之前，是不能checkout切换分支的
 *             但是如果不同的分支在同一个commit阶段 是可以进行checkout分支切换的
 *
 *      如果还没有将某一个功能开发完毕 就要切换分支 建议stash 保存现场
 *
 *     保存现场 :                        git stash
 *     按名称来保存现场:                 git stash save “stashname”
 *     查看保存现场:                     git stash list
 *     还原最近一次的现场:               git stash pop  (如果取出那么list中也会删除)
 *     还原现场不删除list：              git stash appli (同pop 但是不删除list列表)
 *     手动删除现场:                    git stash drop stash@{0}
 *     恢复某一个指定的现场:            git stash apply stash@{1}
 *
 *  stash的冲突问题:
 *     如果一个stash 和 修改后的文件冲突了  那么恢复冲突之后才能恢复现场
 *
 *  TAG 标签 (适用于整个项目的 和 分支没有关系):
 *     创建tag标签
 *          1. git tag  xxx
 *          2. git tag -a xxx -m “xxx”
 *     查看tag标签
 *          git tag
 *     删除tag标签
 *          git tag -d 标签名
 *
 *   查看文件谁写的
 *     git blame a.txt
 *
 *
 */



/**
 *  9. github
 *      
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */






























